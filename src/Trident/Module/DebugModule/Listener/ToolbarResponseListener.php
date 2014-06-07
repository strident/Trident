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

use Trident\Component\Debug\Toolbar\Toolbar;
use Trident\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Debug Toolbar Response Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ToolbarResponseListener
{
    protected $toolbar;

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
     * Response event action.
     *
     * @param  FilterResponseEvent $event
     */
    public function onResponse(FilterResponseEvent $event)
    {
        $kernel   = $event->getKernel();
        $response = $event->getResponse();
        $content  = $response->getContent();

        if (false !== strpos($content, '</body>')) {
            // This needs to be refactored into another class, it also needs to
            // be extendable. I.e. register new 'sections' in the debug info.
            $debug = $this->toolbar->getHtml();
            $debug.= PHP_EOL.'</body>';

            $content = str_replace('</body>', $debug, $content);

            $response->setContent($content);
        }
    }
}
