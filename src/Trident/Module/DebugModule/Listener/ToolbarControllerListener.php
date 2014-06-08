<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\DebugModule\Listener;

use Trident\Component\HttpKernel\Event\FilterControllerEvent;
use Trident\Module\DebugModule\Toolbar\Extension\TridentControllerExtension;

/**
 * Controller Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ToolbarControllerListener
{
    protected $extension;

    /**
     * Constructor.
     *
     * @param TridentControllerExtension $extension
     */
    public function __construct(TridentControllerExtension $extension)
    {
        $this->extension = $extension;
    }

    /**
     * On kernel controller event.
     *
     * @param FilterControllerEvent $event
     */
    public function onController(FilterControllerEvent $event)
    {
        $controller = $this->getClassName($event->getController()[0]);
        $action     = $event->getController()[1];

        $this->extension->setController($controller);
        $this->extension->setAction($action);
    }

    /**
     * {@inheritDoc}
     */
    protected function getClassName($object)
    {
        $name = get_class($object);
        $pos = strrpos($name, '\\');

        return false === $pos
            ? $name
            : substr($name, $pos + 1);
    }
}