<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpKernel\Event;

use Symfony\Component\HttpFoundation\Response;
use Trident\Component\HttpKernel\Event\KernelEvent;

/**
 * Intercept Response Event
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class InterceptResponseEvent extends KernelEvent
{
    protected $response;

    /**
     * Set response.
     *
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Does this event have a response?
     *
     * @return boolean
     */
    public function hasResponse()
    {
        return null !== $this->response;
    }
}
