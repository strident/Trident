<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\MigrationModule;

use Phimple\Container;
use Phinx\Config\Config;
use Symfony\Component\Console\Application;
use Trident\Component\HttpKernel\Module\AbstractModule;
use Trident\Component\HttpKernel\Module\ConsoleModuleInterface;
use Trident\Module\MigrationModule\Console\Command\CreateCommand;
use Trident\Module\MigrationModule\Console\Command\MigrateCommand;
use Trident\Module\MigrationModule\Console\Command\RollbackCommand;
use Trident\Module\MigrationModule\Console\Command\StatusCommand;

/**
 * Migration Module
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentMigrationModule extends AbstractModule implements ConsoleModuleInterface
{
    /**
     * {@inheritDoc}
     */
    public function registerCommands(Application $application)
    {
        $config = $application->getKernel()->getContainer()->get('configuration');

        $phinxConfig = new Config($config->get('migrations'));

        $create   = new CreateCommand();
        $migrate  = new MigrateCommand();
        $rollback = new RollbackCommand();
        $status   = new StatusCommand();

        $create->setConfig($phinxConfig);
        $migrate->setConfig($phinxConfig);
        $rollback->setConfig($phinxConfig);
        $status->setConfig($phinxConfig);

        $application->add($create);
        $application->add($migrate);
        $application->add($rollback);
        $application->add($status);
    }

    /**
     * {@inheritDoc}
     */
    public function registerServices(Container $container)
    {
        $services = require __DIR__.'/module/config/services.php';

        call_user_func($services, $container);
    }

    /**
     * {@inheritDoc}
     */
    public function registerServiceExtensions(Container $container)
    {
    }
}
