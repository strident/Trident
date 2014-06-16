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
 * Bad Request Http Exception
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class BadRequestHttpException extends HttpException
{
    /**
     * Constructor.
     *
     * @param string     $message  The exception message
     * @param \Exception $previous The previous exception
     * @param integer    $code     The exception code
     */
    public function __construct($message, \Exception $previous = null, $code = 0)
    {
        parent::__construct(400, $message, $previous, [], $code);
    }
}
