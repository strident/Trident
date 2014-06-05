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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Trident\Component\HttpKernel\Event\KernelEvent;
use Trident\Component\HttpKernel\HttpKernelInterface;

/**
 * Filter Response Event
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class FilterResponseEvent extends KernelEvent
{
    protected $response;

    /**
     * Constructor.
     *
     * @param HttpKernelInterface $kernel
     * @param Request             $request
     * @param string              $requestType
     * @param Response            $response
     */
    public function __construct(HttpKernelInterface $kernel, Request $request, $requestType, Response $response)
    {
        parent::__construct($kernel, $request, $requestType);

        $this->setResponse($response);
    }

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
}
