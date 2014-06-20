<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\FrameworkModule\Exception;

/**
 * Exception Trace Parser
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ExceptionTraceParser
{
    /**
     * Get normalized array trace.
     *
     * @return array
     */
    public function getNormalized(\Exception $exception)
    {
        $stack = $exception->getTrace();
        $trace = [];

        foreach ($stack as $item) {
            $trace[] = [
                'invocation' => $this->getInvocation($item),
                'origin'     => $this->getOrigin($item)
            ];
        }

        return $trace;
    }

    /**
     * Get invocation of function.
     *
     * @param array $item
     *
     * @return string
     */
    protected function getInvocation(array $item)
    {
        $invocation = '';

        if (isset($item['class']) && isset($item['type'])) {
            $invocation.= $item['class'].$item['type'];
        }

        $invocation.= $item['function'];
        $invocation.= '(';

        if (isset($item['args'])) {
            $args = [];

            foreach ($item['args'] as $arg) {
                $args[] = $this->varToString($arg);
            }

            $invocation.= implode(', ', $args);
        }

        $invocation.= ')';

        return $invocation;
    }

    /**
     * Get origin of call.
     *
     * @param array  $item
     *
     * @return string
     */
    protected function getOrigin(array $item)
    {
        if (isset($item['file']) && isset($item['line'])) {
            return "{$item['file']}({$item['line']})";
        }

        return '[internal function]';
    }

    /**
     * Get type of variable as a string.
     *
     * @param mixed $var
     *
     * @return string
     */
    protected function varToString($var)
    {
        if (is_object($var)) {
            return get_class($var);
        }

        if (is_array($var)) {
            return 'Array';
        }

        if (is_resource($var)) {
            return sprintf('Resource(%s)', get_resource_type($var));
        }

        if (null === $var) {
            return 'null';
        }

        if (false === $var) {
            return 'false';
        }

        if (true === $var) {
            return 'true';
        }

        return (string) "'$var'";
    }
}
