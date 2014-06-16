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

use Trident\Component\HttpKernel\Exception\HttpException;

/**
 * Unauthorized Http Exception
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class UnauthorizedHttpException extends HttpException
{
    /**
     * Constructor.
     *
     * @param string     $challenge A WWW-Authenticate challenge string
     * @param string     $message   The exception message
     * @param \Exception $previous  The previous exception
     * @param integer    $code      The exception code
     */
    public function __construct($challenge, $message, \Exception $previous = null, $code = 0)
    {
        $headers = ['WWW-Authenticate' => $challenge];

        parent::__construct(401, $message, $previous, $headers, $code);
    }
}
