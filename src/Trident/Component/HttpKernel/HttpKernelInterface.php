<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpKernel;

use Symfony\Component\HttpFoundation\Response;
use Trident\Component\HttpFoundation\Request;

/**
 * Http Kernel Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface HttpKernelInterface
{
    const MASTER_REQUEST = 1;
    const SUB_REQUEST = 2;

    /**
     * Handles a Request to convert it to a Response.
     *
     * @param  Request $request
     * @param  integer $type
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST);
}
