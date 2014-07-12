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
use Symfony\Component\Console\Application;
use Symfony\Component\Routing\RouteCollection;
use Trident\Component\HttpKernel\Module\AbstractModule;
use Trident\Component\HttpKernel\Module\ConsoleModuleInterface;
use Trident\Module\FrameworkModule\Console\Command\AssetsCompileCommand;
use Trident\Module\FrameworkModule\Console\Command\AssetsInstallCommand;

/**
 * Framework Module
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentFrameworkModule extends AbstractModule implements ConsoleModuleInterface
{
    /**
     * {@inheritDoc}
     */
    public function registerCommands(Application $application)
    {
        $application->add(new AssetsCompileCommand());
        $application->add(new AssetsInstallCommand());
    }

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
        $services   = require __DIR__.'/module/config/services.php';
        $cache      = require __DIR__.'/module/config/services_cache.php';
        $debug      = require __DIR__.'/module/config/services_debug.php';
        $error      = require __DIR__.'/module/config/services_error.php';
        $form       = require __DIR__.'/module/config/services_form.php';
        $validation = require __DIR__.'/module/config/services_validation.php';

        call_user_func($services, $container);
        call_user_func($cache, $container);
        call_user_func($debug, $container);
        call_user_func($error, $container);
        call_user_func($form, $container);
        call_user_func($validation, $container);
    }

    /**
     * {@inheritDoc}
     */
    public function registerServiceExtensions(Container $container)
    {
        $debug = require __DIR__.'/module/config/service_extensions_debug.php';
        $error = require __DIR__.'/module/config/service_extensions_error.php';

        call_user_func($debug, $container);
        call_user_func($error, $container);
    }

    /**
     * {@inheritDoc}
     */
    public function isCoreModule()
    {
        return true;
    }
}
