<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\FrameworkModule\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Trident\Component\HttpKernel\Module\ConsoleModuleInterface;
use Trident\Component\HttpKernel\AbstractKernel;

/**
 * Trident Console Application
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Application extends BaseApplication
{
    protected $booted = false;

    /**
     * Constructor.
     *
     * @param AbstractKernel $kernel
     */
    public function __construct(AbstractKernel $kernel)
    {
        $this->kernel = $kernel;

        parent::__construct('Trident', $kernel::VERSION);

        $this->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.'));
        $this->getDefinition()->addOption(new InputOption('--no-debug', null, InputOption::VALUE_NONE, 'Switches off debug mode.'));
    }

    /**
     * {@inheritDoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->boot();

        parent::run($input, $output);
    }

    /**
     * Boot console application.
     */
    protected function boot()
    {
        $this->kernel->boot();
        $this->initialiseCommands();

        $this->booted = true;
    }

    /**
     * Initialise commands from modules.
     */
    protected function initialiseCommands()
    {
        $modules = $this->kernel->getModules();

        foreach ($modules as $module) {
            if ( ! $module instanceof ConsoleModuleInterface) {
                continue;
            }

            $module->registerCommands($this);
        }
    }

    /**
     * Get the kernel.
     *
     * @return AbstractKernel
     */
    public function getKernel()
    {
        return $this->kernel;
    }
}
