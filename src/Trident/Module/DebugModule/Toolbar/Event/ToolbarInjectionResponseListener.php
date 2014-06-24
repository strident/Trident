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

use Trident\Component\Debug\Toolbar\Toolbar;
use Trident\Component\HttpKernel\Event\FilterResponseEvent;
use Trident\Component\Templating\Engine\DelegatingEngine;

/**
 * Debug Toolbar Response Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ToolbarInjectionResponseListener
{
    private $engine;
    private $toolbar;

    /**
     * Constructor.
     *
     * @param Toolbar $toolbar
     */
    public function __construct(DelegatingEngine $engine, Toolbar $toolbar)
    {
        $this->engine = $engine;
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

        foreach ($this->toolbar->getExtensions() as $extension) {
            $extension->buildData();
        }

        if (false !== strpos($content, '</body>')) {
            $debug = $this->engine->render('TridentDebugModule:Toolbar:toolbar.html.twig', [
                'extensions' => $this->toolbar->getExtensions()
            ]);

            $content = str_replace('</body>', $debug.PHP_EOL.'</body>', $content);

            $response->setContent($content);
        }
    }
}
