<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\FrameworkModule;

use Trident\Component\HttpKernel\Module\AbstractModule;
use Phimple\Container;
use Symfony\Component\Routing\RouteCollection;

/**
 * Framework Module
 */
class TridentFrameworkModule extends AbstractModule
{
    /**
     * {@inheritDoc}
     */
    public function registerRoutes(RouteCollection $collection)
    {
        $routes     = require __DIR__.'/config/routes.php';
        $registered = call_user_func($routes, $collection);

        // @todo: Throw some exception if registered is false
    }

    /**
     * {@inheritDoc}
     */
    public function registerServices(Container $container)
    {
        $services   = require __DIR__.'/config/services.php';
        $registered = call_user_func($services, $container);

        // @todo: Throw some exception if registered is false
    }
}
