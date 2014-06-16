<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpKernel;

use Phimple\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Trident\Component\Configuration\Configuration;
use Trident\Component\HttpFoundation\Request;
use Trident\Component\HttpKernel\Event\FilterControllerEvent;
use Trident\Component\HttpKernel\Event\FilterExceptionEvent;
use Trident\Component\HttpKernel\Event\FilterResponseEvent;
use Trident\Component\HttpKernel\Event\InterceptResponseEvent;
use Trident\Component\HttpKernel\Event\PostResponseEvent;
use Trident\Component\HttpKernel\Exception\HttpExceptionInterface;
use Trident\Component\HttpKernel\Exception\NotFoundHttpException;
use Trident\Component\HttpKernel\HttpKernelInterface;
use Trident\Component\HttpKernel\KernelEvents;

/**
 * Abstract Kernel
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
abstract class AbstractKernel implements HttpKernelInterface
{
    protected $booted = false;
    protected $configuration = [];
    protected $container;
    protected $debug;
    protected $environment;
    protected $modules;
    protected $name;
    protected $request;
    protected $rootDir;
    protected $session;
    protected $startTime;

    const VERSION         = '0.0.1-alpha';
    const VERSION_ID      = '00101';
    const MAJOR_VERSION   = '0';
    const MINOR_VERSION   = '0';
    const RELEASE_VERSION = '1';
    const EXTRA_VERSION   = 'alpha';

    /**
     * Constructor.
     *
     * @param boolean $debug Whether debugging is enabled or not
     */
    public function __construct($environment, $debug)
    {
        $this->debug       = (bool) $debug;
        $this->environment = $environment;
        $this->name        = $this->getName();
        $this->rootDir     = $this->getRootDir();
        $this->startTime   = microtime(true);
    }

    /**
     * Transform a request into a response.
     *
     * @param  Request $request
     * @param  string  $type
     * @param  boolean $catch
     *
     * @return Response
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $this->request = $request;

        if (false === $this->booted) {
            $this->boot();
        }

        $event = new InterceptResponseEvent($this, $request, $type);
        $this->getDispatcher()->dispatch(KernelEvents::REQUEST, $event);

        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $matcher  = $this->container->get('route_matcher');
        $resolver = $this->container->get('controller_resolver');

        try {
            // Attempt to find the route from the request
            $path    = parse_url($request->query->get('_url'), PHP_URL_PATH);
            $matched = $matcher->match($path);
        } catch (ResourceNotFoundException $e) {
            // If no route is found, throw handle a 404 exception
            $notFoundException = new NotFoundHttpException(sprintf(
                'No route found for path "%s".',
                $request->generateRelative()
            ));

            return $this->handleException($notFoundException, $request, $type);
        }

        $controller = $resolver->getController($request, $matched);
        $arguments  = $resolver->getArguments($request, $controller, $matched);

        $event = new FilterControllerEvent($this, $request, $type);
        $event->setController($controller);
        $event->setArguments($arguments);
        $this->getDispatcher()->dispatch(KernelEvents::CONTROLLER, $event);

        try {
            $response = call_user_func_array($controller, $arguments);
        } catch (\Exception $e) {
            return $this->handleException($e, $request, $type);
        }

        if ( ! $response instanceof Response) {
            $message = sprintf('The controller must return a valid Response (%s given).', $this->varToString($response));

            if (null === $response) {
                $message .= ' Did you forget to add a return statement somewhere in your controller?';
            }

            throw new \LogicException($message);
        }

        return $this->filterResponse($response, $request, $type);
    }

    /**
     * Handle uncaught exceptions in application.
     *
     * @param  Exception $e
     * @param  Request   $request
     * @param  string    $type
     *
     * @return Response
     *
     * @throws \Exception
     */
    protected function handleException(\Exception $e, $request, $type)
    {
        $event = new FilterExceptionEvent($this, $request, $type);
        $event->setException($e);

        $this->getDispatcher()->dispatch(KernelEvents::EXCEPTION, $event);

        $e = $event->getException();

        if ( ! $event->hasResponse()) {
            throw $e;
        }

        $response = $event->getResponse();

        if ( ! $response->isClientError() && ! $response->isServerError() && ! $response->isRedirect()) {
            if ($e instanceof HttpExceptionInterface) {
                $response->setStatusCode($e->getStatusCode());
                $response->headers->add($e->getHeaders());
            } else {
                $response->setStatusCode(500);
            }
        }

        try {
            return $this->filterResponse($response, $request, $type);
        } catch (\Exception $e) {
            return $response;
        }
    }

    /**
     * Filter response.
     *
     * @param  Response $response
     * @param  Request  $request
     * @param  string   $type
     *
     * @return Response
     */
    protected function filterResponse(Response $response, Request $request, $type)
    {
        $event = new FilterResponseEvent($this, $request, $type, $response);
        $this->getDispatcher()->dispatch(KernelEvents::RESPONSE, $event);

        return $event->getResponse();
    }

    /**
     * Terminate the application.
     *
     * @param Request  $request
     * @param Response $response
     */
    public function terminate(Request $request, Response $response)
    {
        $event = new PostResponseEvent($this, $request, $response);
        $this->getDispatcher()->dispatch(KernelEvents::TERMINATE, $event);
    }

    /**
     * Boot the application. Initialise all of the components.
     */
    protected function boot()
    {
        $this->initialiseConfiguration();
        $this->initialiseModules();
        $this->initialiseSession();
        $this->initialiseContainer();

        foreach ($this->modules as $module) {
            $module->boot($this->getContainer());
        }

        $this->booted = true;
    }

    /**
     * Register configuration
     *
     * @param  string $environment
     *
     * @return array
     */
    abstract public function registerConfiguration($environment);

    /**
     * Register modules
     *
     * @param  string $environment
     *
     * @return array
     */
    abstract public function registerModules($environment);

    /**
     * Is the application in debug mode?
     *
     * @return boolean
     */
    public function isDebugMode()
    {
        return (bool) $this->debug;
    }

    /**
     * Get start time.
     *
     * @return float
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Get name of application.
     *
     * @return string
     */
    public function getName()
    {
        if (null === $this->name) {
            $this->name = preg_replace('/[^a-zA-Z0-9_]+/', '', basename($this->rootDir));
        }

        return $this->name;
    }

    /**
     * Get application root directory.
     *
     * @return string
     */
    public function getRootDir()
    {
        if (null === $this->rootDir) {
            $r = new \ReflectionObject($this);
            $this->rootDir = str_replace('\\', '/', dirname($r->getFileName()));
        }

        return $this->rootDir;
    }

    /**
     * Get application cache directory.
     *
     * @return string
     */
    public function getCacheDir()
    {
        return $this->rootDir.'/cache';
    }

    /**
     * Get application log directory.
     *
     * @return string
     */
    public function getLogDir()
    {
        return $this->rootDir.'/logs';
    }

    /**
     * Get application character set.
     *
     * @return string
     */
    public function getCharset()
    {
        return 'UTF-8';
    }

    /**
     * Get event dispatcher
     *
     * @return Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function getDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }

    public function getModule($name)
    {
        return $this->modules[$name];
    }

    /**
     * Initialise the application configuration
     *
     * @return Configuration
     */
    protected function initialiseConfiguration()
    {
        $configuration = $this->registerConfiguration($this->environment);

        if ( ! is_array($configuration)) {
            throw new \RuntimeException(sprintf(
                'The configuration must be a valid array (%s given).',
                $this->varToString($configuration)
            ));
        }

        return $this->configuration = new Configuration($configuration);
    }

    /**
     * Get configuration
     *
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Initialise the modules, register them in the kernel
     *
     * @return array
     */
    protected function initialiseModules()
    {
        $this->modules = array();

        // @todo: environments!
        foreach ($this->registerModules($this->environment) as $module) {
            $name = $module->getName();

            if (isset($this->modules[$name])) {
                throw new \LogicException(sprintf('Trying to register two modules with the same name "%s"', $name));
            }

            $this->modules[$module->getName()] = $module;
        }

        return $this->modules;
    }

    /**
     * Initialise the session, and attach it to the request.
     *
     * @return Session
     */
    protected function initialiseSession()
    {
        $session = new Session();
        $session->start();

        $this->request->setSession($session);

        return $this->session = $session;
    }

    /**
     * Builds the service container.
     *
     * @return Container
     */
    protected function buildContainer()
    {
        return new Container();
    }

    /**
     * Initialise the service container
     *
     * @return Container
     */
    protected function initialiseContainer()
    {
        // @todo: add some form of container caching?
        $container = $this->buildContainer();
        $this->prepareContainer($container);

        $this->container = $container;
        return $this->container;
    }

    /**
     * Insert services into the container at application boot
     *
     * @param  Container $container
     */
    protected function prepareContainer(Container $container)
    {
        foreach ($this->getKernelParameters() as $key => $value) {
            $container[$key] = $value;
        }

        $container->set('kernel', $this);
        $container->set('configuration', $this->configuration);
        $container->set('request', $this->request);
        $container->set('session', $this->session);

        foreach ($this->modules as $module) {
            $module->registerServices($container);
        }
    }

    /**
     * Get container.
     *
     * @return Container
     */
    private function getContainer()
    {
        return $this->container;
    }

    /**
     * Get type of variable as a string.
     *
     * @param mixed $var
     *
     * @return string
     */
    protected function varToString($var)
    {
        if (is_object($var)) {
            return sprintf('Object(%s)', get_class($var));
        }

        if (is_array($var)) {
            $a = array();
            foreach ($var as $k => $v) {
                $a[] = sprintf('%s => %s', $k, $this->varToString($v));
            }

            return sprintf("Array(%s)", implode(', ', $a));
        }

        if (is_resource($var)) {
            return sprintf('Resource(%s)', get_resource_type($var));
        }

        if (null === $var) {
            return 'null';
        }

        if (false === $var) {
            return 'false';
        }

        if (true === $var) {
            return 'true';
        }

        return (string) "'$var'";
    }

    /**
     * Get kernel parameters
     *
     * @return array
     */
    public function getKernelParameters()
    {
        return [
            'kernel.debug'       => $this->debug,
            'kernel.environment' => $this->environment,
            'kernel.cache_dir'   => $this->getCacheDir(),
            'kernel.charset'     => $this->getCharset(),
            'kernel.logs_dir'    => $this->getLogDir(),
            'kernel.name'        => $this->getName(),
            'kernel.root_dir'    => $this->getRootDir(),
        ];
    }
}
