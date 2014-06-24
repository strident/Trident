<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\DebugModule\Toolbar\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Trident\Component\Debug\Toolbar\Toolbar;

/**
 * Toolbar Subscription Manager
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ToolbarSubscriptionManager
{
    private $registered = false;
    private $toolbar;

    /**
     * Constructor.
     *
     * @param Toolbar $toolbar
     */
    public function __construct(Toolbar $toolbar)
    {
        $this->toolbar = $toolbar;
    }

    /**
     * Register the subscriptions for extensions that have them.
     */
    public function registerSubscriptions(EventDispatcher $dispatcher)
    {
        if ($this->registered) {
            throw new \RuntimeException('Cannot register debug toolbar subscriptions when already registered.');
        }

        foreach ($this->toolbar->getExtensions() as $extension) {
            if ( ! $extension instanceof EventSubscriberInterface) {
                continue;
            }

            $dispatcher->addSubscriber($extension);
        }

        $this->registered = true;
    }
}
