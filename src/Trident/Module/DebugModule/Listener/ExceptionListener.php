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

use Symfony\Component\HttpFoundation\Response;
use Trident\Component\HttpKernel\Event\FilterExceptionEvent;

/**
 * Debug Exception Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ExceptionListener
{
    public function onException(FilterExceptionEvent $event)
    {
        $kernel = $event->getKernel();

        if ( ! $kernel->isDebugMode()) {
            $event->setResponse(new Response($this->createBody(), 500));
            return;
        }

        $event->setResponse(new Response('This still needs to be done.', 500));
    }

    protected function createBody()
    {
        return <<<EOF
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="robots" content="noindex,nofollow" />
        <title>Internal Error</title>
    </head>
    <body>
        <p>An internal error has occurred. Please try again later.</p>
    </body>
</html>
EOF;
    }
}
