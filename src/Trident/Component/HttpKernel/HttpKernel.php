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
use Symfony\Component\HttpKernel\HttpKernel as SymfonyHttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Trident\Component\HttpKernel\Event\ResponseEvent;

/**
 * Trident HTTP Kernel
 */
class HttpKernel implements HttpKernelInterface, TerminableInterface
{
    protected $booted = false;
    protected $container;
    protected $debug;
    protected $modules;
    protected $name;
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
    public function __construct($debug)
    {
        $this->debug   = (bool) $debug;
        $this->rootDir = $this->getRootDir();
        $this->name    = $this->getName();

        if ($this->debug) {
            $this->startTime = microtime(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        if (false === $this->booted) {
            $this->boot();
        }

        return $this->getHttpKernel()->handle($request, $type, $catch);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->initialiseModules();
        $this->initialiseContainer();

        foreach ($this->modules as $module) {
            $module->boot($this->getContainer());
        }

        $this->booted = true;
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response)
    {
        if (false === $this->booted) {
            return;
        }

        if ($this->getHttpKernel() instanceof TerminableInterface) {
            $this->getHttpKernel()->terminate($request, $response);
        }
    }

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
        return $this->container['event_dispatcher'];
    }

    /**
     * Get HttpKernel
     *
     * @return SymfonyHttpKernel
     */
    public function getHttpKernel()
    {
        return $this->container['http_kernel'];
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
        foreach ($this->registerModules(null) as $module) {
            $name = $module->getName();

            if (isset($this->modules[$name])) {
                throw new \LogicException(sprintf('Trying to register two bundles with the same name "%s"', $name));
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
            $container["%$key%"] = $value;
        }

        $container['kernel'] = $this;

        foreach ($this->modules as $module) {
            $module->registerServices($container);
        }
    }

    /**
     * Get kernel parameters
     *
     * @return array
     */
    public function getKernelParameters()
    {
        return [
            'kernel.debug'     => $this->debug,
            'kernel.cache_dir' => $this->getCacheDir(),
            'kernel.charset'   => $this->getCharset(),
            'kernel.logs_dir'  => $this->getLogDir(),
            'kernel.name'      => $this->getName(),
            'kernel.root_dir'  => $this->getRootDir(),
        ];
    }
}
