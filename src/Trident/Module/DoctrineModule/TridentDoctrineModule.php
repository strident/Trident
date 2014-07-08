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

use Doctrine\Common\ClassLoader;
use Phimple\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Routing\RouteCollection;
use Trident\Component\HttpKernel\Module\AbstractModule;
use Trident\Component\HttpKernel\Module\ConsoleModuleInterface;

/**
 * Doctrine Module
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentDoctrineModule extends AbstractModule implements ConsoleModuleInterface
{
    /**
     * {@inheritDoc}
     */
    public function registerCommands(Application $application)
    {
        $conn = $application->getKernel()->getContainer()->get('doctrine.dbal.connection');
        $em   = $application->getKernel()->getContainer()->get('doctrine.orm.entity_manager');

        $helperSet = $application->getHelperSet();
        $helperSet->set(new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($conn), 'connection');
        $helperSet->set(new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em), 'em');

        $application->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand());
        $application->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand());
        $application->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand());
        $application->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand());
        $application->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand());
        $application->add(new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand());
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
        $services = require __DIR__.'/module/config/services.php';
        $dbal     = require __DIR__.'/module/config/services_dbal.php';
        $orm      = require __DIR__.'/module/config/services_orm.php';

        call_user_func($services, $container);
        call_user_func($dbal, $container);
        call_user_func($orm, $container);
    }

    /**
     * {@inheritDoc}
     */
    public function registerServiceExtensions(Container $container)
    {
        $extensions = require __DIR__.'/module/config/service_extensions.php';
        $orm        = require __DIR__.'/module/config/service_extensions_orm.php';

        call_user_func($extensions, $container);
        call_user_func($orm, $container);
    }
}
