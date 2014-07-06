<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpKernel\Module;

use Phimple\Container;
use Symfony\Component\Routing\RouteCollection;

/**
 * Abstract Module
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
abstract class AbstractModule
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Register module routes in the application
     *
     * @param RouteCollection $collection
     */
    abstract public function registerRoutes(RouteCollection $collection);

    /**
     * Register module services in the container
     *
     * @param Container $container
     */
    abstract public function registerServices(Container $continer);

    /**
     * Register module service extensions in the container
     *
     * @param Container $container
     */
    abstract public function registerServiceExtensions(Container $container);

    /**
     * Boot the module
     *
     * @param Container $container
     */
    public function boot(Container $container)
    {
        $this->registerServices($container);
        $this->registerRoutes($container->get('route_collection'));
    }

    /**
     * When all modules have booted, run through again calling this
     *
     * @param Container $container
     */
    public function postBoot(Container $container)
    {
        $this->registerServiceExtensions($container);
    }

    /**
     * Get root directory of module
     *
     * @return string
     */
    public function getRootDir()
    {
        $reflection = new \ReflectionClass($this);

        return dirname($reflection->getFileName());
    }

    /**
     * Returns the module name (the class short name).
     *
     * @return string
     */
    final public function getName()
    {
        if (null !== $this->name) {
            return $this->name;
        }

        $name = get_class($this);
        $pos = strrpos($name, '\\');

        return $this->name = false === $pos ? $name : substr($name, $pos + 1);
    }

    /**
     * Is this module a core module? Must be overridden.
     *
     * @return boolean
     */
    public function isCoreModule()
    {
        return false;
    }
}
