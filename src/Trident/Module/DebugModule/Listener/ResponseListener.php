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

/**
 * Debug Response Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ResponseListener
{
    public function onResponse(FilterResponseEvent $event)
    {
        $kernel   = $event->getKernel();

        $response = $event->getResponse();
        $content  = $response->getContent();

        $runtime = round((microtime(true) - $kernel->getStartTime()) * 1000, 2);
        $memory  = round(memory_get_peak_usage() / 1048576, 2);

        $content.= <<<EOF
<br /><br />
Rendered in {$runtime}ms. With peak memory usage of {$memory}MiB.
EOF;

        $response->setContent($content);
    }
}
