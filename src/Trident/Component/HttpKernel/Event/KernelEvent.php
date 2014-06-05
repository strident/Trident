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

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Trident\Component\HttpKernel\HttpKernelInterface;

/**
 * Kernel Event
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class KernelEvent extends Event
{
    protected $kernel;
    protected $request;
    protected $requestType;

    /**
     * Constructor.
     *
     * @param HttpKernelInterface $kernel
     * @param Request             $request
     * @param integer             $requestType
     */
    public function __construct(HttpKernelInterface $kernel, Request $request, $requestType)
    {
        $this->kernel = $kernel;
        $this->request = $request;
        $this->requestType = $requestType;
    }

    /**
     * Get kernel.
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
     * Get request type.
     *
     * @return integer
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * Is the request a master request?
     *
     * @return boolean
     */
    public function isMasterRequest()
    {
        return HttpKernelInterface::MASTER_REQUEST === $this->requestType;
    }
}
