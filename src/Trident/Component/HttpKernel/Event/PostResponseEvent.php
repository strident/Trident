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
use Trident\Component\HttpKernel\HttpKernelInterface;

/**
 * Post-response Event
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class PostResponseEvent extends KernelEvent
{
    protected $kernel;
    protected $request;
    protected $response;

    /**
     * Constructor.
     *
     * @param HttpKernelInterface $kernel
     * @param Request             $request
     * @param Response            $response
     */
    public function __construct(HttpKernelInterface $kernel, Request $request, Response $response)
    {
        $this->kernel = $kernel;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Get kernel that triggered event.
     *
     * @return HttpKernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Get request.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
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
