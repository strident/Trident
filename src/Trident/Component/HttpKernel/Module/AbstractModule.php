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

    public function boot(Container $container)
    {
        $this->registerRoutes($container['route_collection']);
    }

    /**
     * Returns the bundle name (the class short name).
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
}
