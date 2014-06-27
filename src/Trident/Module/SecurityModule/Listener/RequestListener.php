<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\SecurityModule\Listener;

use Trident\Component\HttpKernel\Event\FilterRequestEvent;

/**
 * Security Request Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class RequestListener
{
    public function onRequest(FilterRequestEvent $event)
    {
        $kernel    = $event->getKernel();
        $container = $kernel->getContainer();

        if ( ! $container->has('security')) {
            return;
        }

        $security = $container->get('security');
        $security->initialize();
    }
}
