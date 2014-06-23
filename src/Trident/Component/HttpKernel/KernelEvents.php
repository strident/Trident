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

/**
 * Kernel Events
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
final class KernelEvents
{
    const BOOT       = 'kernel.boot';
    const REQUEST    = 'kernel.request';
    const RESPONSE   = 'kernel.response';
    const EXCEPTION  = 'kernel.exception';
    const CONTROLLER = 'kernel.controller';
    const VIEW       = 'kernel.view';
    const TERMINATE  = 'kernel.terminate';
}
