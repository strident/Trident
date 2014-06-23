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

use Trident\Component\HttpKernel\Event\FilterResponseEvent;
use Trident\Module\DebugModule\Toolbar\Extension\TridentSecurityExtension;

/**
 * Debug Toolbar Security Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ToolbarSecurityListener
{
    private $extension;

    /**
     * Constructor.
     *
     * @param TridentSecurityExtension $extension
     */
    public function __construct(TridentSecurityExtension $extension)
    {
        $this->extension = $extension;
    }

    /**
     * On kernel response event.
     *
     * @param  FilterResponseEvent $event
     */
    public function onResponse(FilterResponseEvent $event)
    {
        $this->extension->buildData();
    }
}
