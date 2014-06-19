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
 * Module asset compile command
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class AssetsCompileCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        // Meta
        $this->setName('assets:compile');
        $this->setDescription('Compile assets installed that are used in Twig views.');

        // Options
        $this->addOption('env', 'e', InputOption::VALUE_OPTIONAL, 'Environment to compile assets for.', 'dev');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel  = $this->getApplication()->getKernel();
        $modules = $kernel->getModules();

        $output->writeln(sprintf(
            'Compiling assets for <info>%s</info> modules for <comment>"%s"</comment> environment.',
            count($modules),
            $input->getOption('env')
        ));

        $output->writeln('');

        $filesystem = new FileSystem();

        foreach ($modules as $module) {
            $output->write(sprintf(
                'Parsing templates in "%s" module', $module->getName()
            ));

            try {
                // $filesystem->symlink($from, $to);

                // If assets were found, and successfully installed:
                $output->writeln('... <info>Done</info>');
            } catch(IOExceptionInterface $e) {
                // If there were any problems installing assets:
                $output->writeln('... <error>Error</error>');
            }
        }
    }
}