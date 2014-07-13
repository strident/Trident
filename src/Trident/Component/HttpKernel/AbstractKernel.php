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
use Trident\Component\HttpKernel\Event\FilterRequestEvent;
use Trident\Component\HttpKernel\Event\FilterResponseEvent;
use Trident\Component\HttpKernel\Event\InterceptResponseEvent;
use Trident\Component\HttpKernel\Event\PostBootEvent;
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
    protected $safeMode = false;
    protected $session;
    protected $startTime;

    const VERSION         = '1.0.5-alpha3';
    const VERSION_ID      = '10005';
    const MAJOR_VERSION   = '1';
    const MINOR_VERSION   = '0';
    const RELEASE_VERSION = '5';
    const EXTRA_VERSION   = 'alpha3';

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

        // Create a 'base' request, mainly for CLI
        $this->request = new Request();
    }

    /**
     * Transform a request into a response.
     *
     * @param Request $request
     * @param string  $type
     *
     * @return Response
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST)
    {
        try {
            $response = $this->handleRequest($request, $type);
        } catch (\Exception $e) {
            $response = $this->handleException($e, $request, $type);
        }

        return $response;
    }

    /**
     * Transform a request into a response.
     *
     * @param Request $request
     * @param string  $type
     *
     * @return Response
     */
    protected function handleRequest(Request $request, $type)
    {
        $this->request = $request;

        if (false === $this->booted) {
            $this->boot();

            // Only fire this when handling a request
            $event = new PostBootEvent($this, $this->request, self::MASTER_REQUEST);
            $this->getDispatcher()->dispatch(KernelEvents::BOOT, $event);
        }

        // Attach the current application session to the current request
        $this->request->setSession($this->getSession());

        $event = new InterceptResponseEvent($this, $request, $type);
        $this->getDispatcher()->dispatch(KernelEvents::POSTBOOT, $event);

        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $event = new FilterRequestEvent($this, $request, $type);
        $this->getDispatcher()->dispatch(KernelEvents::REQUEST, $event);

        // Match the route to a controller
        $matched  = $this->matchRoute($request, $type);
        $resolver = $this->container->get('controller_resolver');

        $controller = $resolver->getController($request, $matched);
        $arguments  = $resolver->getArguments($request, $controller, $matched);

        $event = new FilterControllerEvent($this, $request, $type);
        $event->setController($controller);
        $event->setArguments($arguments);
        $this->getDispatcher()->dispatch(KernelEvents::CONTROLLER, $event);

        // Attempt to get a response from the controller
        $response = call_user_func_array($controller, $arguments);

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
     * Match a route to a controller.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function matchRoute(Request $request, $type)
    {
        $router = $this->container->get('router');

        if ( ! $request->attributes->has('_controller')) {
            try {
                // Attempt to find the route from the request
                $matched = $router->match($request->getPathInfo());
            } catch (ResourceNotFoundException $e) {
                // If no route is found, throw handle a 404 exception
                throw new NotFoundHttpException(sprintf(
                    'No route found for path "%s".',
                    $request->getPath()
                ));
            }
        } else {
            // This will usually be the case if a kernel exception is thrown
            $matched = ['_controller' => $request->attributes->get('_controller')];
        }

        return $matched;
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
    public function boot()
    {
        try {
            $this->initialiseConfiguration();
            $this->initialiseSession();
            $this->initialiseContainer();
            $this->initialiseModules();
        } catch (\Exception $e) {
            $this->setSafeMode(true);

            // Attempt to boot in safe mode to allow the application to properly
            // handle the exception - if possible.
            $this->boot();

            throw $e;
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
     * Is kernel in safe mode?
     *
     * @return boolean
     */
    public function isSafeMode()
    {
        return (bool) $this->safeMode;
    }

    /**
     * Set safe mode.
     *
     * @param boolean $safeMode
     *
     * @return AbstractKernel
     */
    protected function setSafeMode($safeMode)
    {
        $this->safeMode = (bool) $safeMode;

        return $this;
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
        $exceptions = array();

        foreach ($this->registerModules($this->environment) as $module) {
            if ($this->isSafeMode() && ! $module->isCoreModule()) {
                continue;
            }

            $name = $module->getName();

            if (isset($this->modules[$name])) {
                throw new \LogicException(sprintf('Trying to register two modules with the same name "%s"', $name));
            }

            $module->boot($this->getContainer());

            $this->modules[$module->getName()] = $module;
        }

        // Go through registered modules and execute post-boot actions
        foreach ($this->modules as $module) {
            $module->postBoot($this->getContainer());
        }

        return $this->modules;
    }

    /**
     * Get a module.
     *
     * @param string $name
     *
     * @return AbstractModule
     */
    public function getModule($name)
    {
        return $this->modules[$name];
    }

    /**
     * Get the modules/
     *
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Initialise the session, and attach it to the request.
     *
     * @return Session
     */
    protected function initialiseSession()
    {
        $session = $this->session ?: new Session();

        if ( ! $session->isStarted()) {
            $session->start();
        }

        return $this->session = $session;
    }

    /**
     * Get the application session.
     *
     * @return Session
     */
    protected function getSession()
    {
        return $this->session;
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
    }

    /**
     * Get container.
     *
     * @return Container
     */
    public function getContainer()
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
            'kernel.version'     => self::VERSION
        ];
    }
}
