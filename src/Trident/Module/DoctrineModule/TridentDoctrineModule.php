<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\DoctrineModule;

use Phimple\Container;
use Symfony\Component\Routing\RouteCollection;
use Trident\Component\HttpKernel\Module\AbstractModule;

/**
 * Doctrine Module
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentDoctrineModule extends AbstractModule
{
    /**
     * {@inheritDoc}
     */
    public function registerRoutes(RouteCollection $collection)
    {
        $routes = require __DIR__.'/module/config/routes.php';

        call_user_func($routes, $collection);
    }

    /**
     * {@inheritDoc}
     */
    public function registerServices(Container $container)
    {
        $dbal = require __DIR__.'/module/config/services_dbal.php';
        $orm  = require __DIR__.'/module/config/services_orm.php';

        call_user_func($dbal, $container);
        call_user_func($orm, $container);
    }
}
