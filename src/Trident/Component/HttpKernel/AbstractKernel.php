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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Trident\Component\Configuration\Configuration;
use Trident\Component\HttpKernel\HttpKernelInterface;
use Trident\Component\HttpKernel\Event\ResponseEvent;

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
    protected $startTime;

    const VERSION         = '0.0.1';
    const VERSION_ID      = '00101';
    const MAJOR_VERSION   = '0';
    const MINOR_VERSION   = '0';
    const RELEASE_VERSION = '1';
    const EXTRA_VERSION   = '';

    /**
     * Constructor.
     *
     * @param boolean $debug Whether debugging is enabled or not
     */
    public function __construct($environment, $debug)
    {
        $this->debug       = (bool) $debug;
        $this->environment = $environment;
        $this->rootDir     = $this->getRootDir();
        $this->name        = $this->getName();

        if ($this->debug) {
            $this->startTime = microtime(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $this->request = $request;

        if (false === $this->booted) {
            $this->boot();
        }

        $matcher  = $this->container->get('route_matcher');
        $resolver = $this->container->get('controller_resolver');

        $path    = parse_url($request->getURI(), PHP_URL_PATH);
        $matched = $matcher->match($path);

        $controller = $resolver->getController($request, $matched);
        $arguments  = $resolver->getArguments($request, $controller, $matched);

        $response = call_user_func_array($controller, $arguments);

        if ( ! $response instanceof Response) {
            $message = sprintf('The controller must return a valid Response (%s given).', $this->varToString($response));

            if (null === $response) {
                $message .= ' Did you forget to add a return statement somewhere in your controller?';
            }

            throw new \LogicException($message);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->initialiseConfiguration();
        $this->initialiseModules();
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
     * @return array
     */
    abstract public function registerConfiguration($environment);

    /**
     * Register modules
     *
     * @param  string $environment
     * @return array
     */
    abstract public function registerModules($environment);

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        if (null === $this->name) {
            $this->name = preg_replace('/[^a-zA-Z0-9_]+/', '', basename($this->rootDir));
        }

        return $this->name;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return $this->rootDir.'/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return $this->rootDir.'/logs';
    }

    /**
     * {@inheritdoc}
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
        // return $this->container->get('event_dispatcher');
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
    public function initialiseConfiguration()
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
    public function initialiseModules()
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
     * Get container
     *
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Initialise the service container
     *
     * @return Container
     */
    public function initialiseContainer()
    {
        // @todo: add some form of container caching?
        $container = $this->buildContainer();
        $this->prepareContainer($container);

        $this->container = $container;
        return $this->container;
    }

    /**
     * Builds the service container.
     *
     * @return Container
     */
    public function buildContainer()
    {
        return new Container();
    }

    /**
     * Insert services into the container at application boot
     *
     * @param  Container $container
     */
    public function prepareContainer(Container $container)
    {
        foreach ($this->getKernelParameters() as $key => $value) {
            $container[$key] = $value;
        }

        $container->set('kernel', $this);
        $container->set('configuration', $this->configuration);
        $container->set('request', $this->request);

        foreach ($this->modules as $module) {
            $module->registerServices($container);
        }
    }

    private function varToString($var)
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
