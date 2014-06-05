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

use Trident\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * HTTP Exception
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class HttpException extends \RuntimeException implements HttpExceptionInterface
{
    protected $statusCode;
    protected $headers;

    /**
     * Constructor.
     *
     * @param integer    $statusCode
     * @param string     $message
     * @param \Exception $previous
     * @param array      $headers
     * @param integer    $code
     */
    public function __construct($statusCode, $message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        $this->headers = $headers;
        $this->statusCode = $statusCode;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get status code.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
