<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\FrameworkModule\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Module asset install command
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class AssetsInstallCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    public function configure()
    {
        // Meta
        $this->setName('assets:install');
        $this->setDescription('Install assets from modules registered in Trident.');

        // Arguments
        // @todo
        // $this->addArgument('name', InputArgument::OPTIONAL, 'Module to install assets for.');

        // Options
        // @todo: '-s|--symlink' is currently going to be the default / only option.
        // $this->addOption('symlink', 's', InputOption::VALUE_NONE, 'If set, the assets will be installed via symlink.');
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel  = $this->getApplication()->getKernel();
        $modules = $kernel->getModules();

        $output->writeln(sprintf(
            'Installing assets for <info>%s</info> modules.',
            count($modules)
        ));

        $output->writeln('');

        $filesystem = new FileSystem();

        foreach ($modules as $module) {
            $output->write(sprintf(
                'Installing assets for "%s"', $module->getName()
            ));

            $from = $module->getRootDir().'/module/public';
            $to   = $kernel->getRootDir().'/../public/modules/'.strtolower($module->getName());

            if ( ! $filesystem->exists($from)) {
                // If there were no assets to install
                $output->writeln('... <comment>N/A</comment>');

                // Skip to next module
                continue;
            }

            try {
                $filesystem->symlink($from, $to);

                // If assets were found, and successfully installed:
                $output->writeln('... <info>Done</info>');
            } catch(IOExceptionInterface $e) {
                // If there were any problems installing assets:
                $output->writeln('... <error>Error</error>');
            }
        }
    }
}
