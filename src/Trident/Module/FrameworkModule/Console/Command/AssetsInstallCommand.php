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

        $output->writeln(sprintf('Installing assets for <info>%s</info> modules.', count($modules)));
        $output->writeln('');

        // Flush existing assets
        if ( ! $this->flushAssets($kernel, $output)) {
            // Halt if assets couldn't be flushed
            return;
        }

        // Install core application assets (if any)
        $this->installAssets($kernel, 'core', $kernel->getRootDir().'/public', $output);

        // Install module assets (if any)
        foreach ($modules as $module) {
            $from = $module->getRootDir().'/module/public';

            $this->installAssets($kernel, $module->getName(), $from, $output);
        }
    }

    /**
     * Install assets from on directory to another.
     *
     * @param \TridentKernel  $kernel
     * @param string          $name
     * @param string          $from
     * @param OutputInterface $output
     */
    private function installAssets(\TridentKernel $kernel, $name, $from, OutputInterface $output)
    {
        $output->write(sprintf('Installing assets for "%s"', $name));

        $filesystem = new FileSystem();

        if ( ! $filesystem->exists($from)) {
            // If there were no assets to install
            $output->writeln('... <comment>N/A</comment>');
            return;
        }

        try {
            $filesystem->symlink($from, $kernel->getAssetDir().'/'.strtolower($name));

            // If assets were found, and successfully installed:
            $output->writeln('... <info>Done</info>');
        } catch(IOExceptionInterface $e) {
            // If there were any problems installing assets:
            $output->writeln('... <error>Error</error>');
        }
    }

    /**
     * Flush all existing assets
     *
     * @param TridentKernel   $kernel
     * @param OutputInterface $output
     *
     * @return boolean
     */
    private function flushAssets(\TridentKernel $kernel, OutputInterface $output)
    {
        $filesystem = new FileSystem();

        $output->write('Clearing existing assets');

        try {
            if ($filesystem->exists($kernel->getAssetDir())) {
                $filesystem->remove($kernel->getAssetDir());
            }

            $output->writeln('... <info>Done</info>');
            $output->writeln('');

            return true;
        } catch (\Exception $e) {
            $output->writeln('... <error>Error</error>');
            $output->writeln('');

            return false;
        }
    }
}
