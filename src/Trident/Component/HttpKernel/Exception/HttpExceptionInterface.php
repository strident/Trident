<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpKernel\Exception;

/**
 * HTTP Exception Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface HttpExceptionInterface
{
    public function getHeaders();
    public function getStatusCode();
}
