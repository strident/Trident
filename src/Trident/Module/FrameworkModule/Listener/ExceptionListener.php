<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\FrameworkModule\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Trident\Component\HttpKernel\Event\FilterExceptionEvent;
use Trident\Component\HttpKernel\HttpKernelInterface;

/**
 * Kernel Exception Listener
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ExceptionListener
{
    protected $controller;
    protected $logger;

    /**
     * Constructor.
     *
     * @param string          $controller
     * @param LoggerInterface $logger
     */
    public function __construct($controller, LoggerInterface $logger = null)
    {
        $this->controller = $controller;
        $this->logger = $logger;
    }

    /**
     * Exception event handler.
     *
     * @param FilterExceptionEvent $event
     */
    public function onException(FilterExceptionEvent $event)
    {
        static $handling;

        if (true === $handling) {
            return false;
        }

        $handling = true;

        $exception = $event->getException();
        $request   = $event->getRequest();


        // This would be where we'd log, make another method that checks if logger is there and logs.

        $request = $this->duplicateRequest($exception, $request);

        try {
            $response = $event->getKernel()->handle($request, HttpKernelInterface::SUB_REQUEST);
        } catch (\Exception $e) {
            // Log exception being thrown when trying to hanble an exception.

            // Set handling to false otherwise it wont be able to handle further more
            $handling = false;

            // Re-throw the exception from within the kernel.
            return;
        }

        $event->setResponse($response);

        $handling = false;
    }

    /**
     * Creates a request for the exception to the exception controller.
     *
     * @param Exception $exception
     * @param Request   $request
     *
     * @return Request
     */
    protected function duplicateRequest(\Exception $exception, Request $request)
    {
        $attributes = [
            '_controller' => $this->controller,
            'exception'   => $exception,
            'format'      => $request->getRequestFormat()
        ];

        $request = $request->duplicate(null, null, $attributes);
        $request->setMethod('GET');

        return $request;
    }
}
