<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpKernel\Module;

use Symfony\Component\Console\Application;

/**
 * Console-enabled Module Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface ConsoleModuleInterface
{
    /**
     * Register console commands.
     *
     * @param Application $application
     */
    public function registerCommands(Application $application);
}
