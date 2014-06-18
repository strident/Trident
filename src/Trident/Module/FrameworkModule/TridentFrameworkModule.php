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

use Phimple\Container;
use Symfony\Component\Routing\RouteCollection;
use Trident\Component\HttpKernel\Module\AbstractModule;

/**
 * Framework Module
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentFrameworkModule extends AbstractModule
{
    /**
     * {@inheritDoc}
     */
    public function registerRoutes(RouteCollection $collection)
    {
        $routes     = require __DIR__.'/module/config/routes.php';
        call_user_func($routes, $collection);
    }

    /**
     * {@inheritDoc}
     */
    public function registerServices(Container $container)
    {
        $services = require __DIR__.'/module/config/services.php';
        $cache    = require __DIR__.'/module/config/services_cache.php';
        $error    = require __DIR__.'/module/config/services_error.php';

        call_user_func($services, $container);
        call_user_func($cache, $container);
        call_user_func($error, $container);
    }
}
