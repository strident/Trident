<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpFoundation;

use Trident\Component\HttpFoundation\Request;

/**
 * Request Stack
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class RequestStack
{
    private $requests = [];

    /**
     * Push a request onto the stack.
     *
     * @param Request $request
     *
     * @return RequestStack
     */
    public function push(Request $request)
    {
        $this->requests[] = $request;

        return $this;
    }

    /**
     * Pops the current request from the stack.
     *
     * @return Request
     */
    public function pop()
    {
        if ( ! $this->requests) {
            return;
        }

        return array_pop($this->requests);
    }

    /**
     * Get the current request.
     *
     * @return Request|null
     */
    public function getCurrentRequest()
    {
        return end($this->requests) ?: null;
    }

    /**
     * Get the master request.
     *
     * @return Request|void
     */
    public function getMasterRequest()
    {
        if ( ! $this->requests) {
            return;
        }

        return $this->requests[0];
    }
}
