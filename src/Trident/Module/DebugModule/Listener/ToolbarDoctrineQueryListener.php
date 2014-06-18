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

use Doctrine\DBAL\Logging\DebugStack;
use Trident\Component\HttpKernel\Event\FilterResponseEvent;
use Trident\Module\DebugModule\Toolbar\Extension\TridentDoctrineQueryExtension;

/**
 * Debug Toolbar Doctrine Query Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ToolbarDoctrineQueryListener
{
    private $extension;
    private $stack;

    /**
     * Constructor.
     *
     * @param DebugStack                    $stack
     * @param TridentDoctrineQueryExtension $extension
     */
    public function __construct(DebugStack $stack, TridentDoctrineQueryExtension $extension)
    {
        $this->extension = $extension;
        $this->stack = $stack;
    }

    /**
     * On kernel response event.
     *
     * @param  FilterResponseEvent $event
     */
    public function onResponse(FilterResponseEvent $event)
    {
        $this->extension->setStack($this->stack);
        $this->extension->buildData();
    }
}
