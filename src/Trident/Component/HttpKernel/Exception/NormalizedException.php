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
 * Normalized Exception
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class NormalizedException
{
    private $exception;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    public function getMessage()
    {
        return $this->exception->getMessage();
    }

    public function getStatusCode()
    {
        if ($this->exception instanceof HttpExceptionInterface) {
            return $this->exception->getStatusCode();
        }

        return 500;
    }

    public function getNormalizedTrace()
    {
        $parser = new ExceptionTraceParser();

        return $parser->getNormalized($this->exception);
    }

    public function getType()
    {
        $name = get_class($this->exception);
        $pos = strrpos($name, '\\');

        return false === $pos
            ? $name
            : substr($name, $pos + 1);
    }

    public function getFullType()
    {
        return get_class($this->exception);
    }
}
