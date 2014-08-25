<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\TemplatingModule;

use Phimple\Container;
use Symfony\Component\Routing\RouteCollection;
use Trident\Component\HttpKernel\Module\AbstractModule;

/**
 * Framework Module
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentTemplatingModule extends AbstractModule
{
    /**
     * {@inheritDoc}
     */
    public function registerServices(Container $container)
    {
        $services = require __DIR__.'/module/config/services.php';
        $form     = require __DIR__.'/module/config/services_form.php';

        call_user_func($services, $container);
        call_user_func($form, $container);
    }

    /**
     * {@inheritDoc}
     */
    public function registerServiceExtensions(Container $container)
    {
        $extensions = require __DIR__.'/module/config/service_extensions.php';

        call_user_func($extensions, $container);
    }

    /**
     * {@inheritDoc}
     */
    public function isCoreModule()
    {
        return true;
    }
}
