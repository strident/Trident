<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\FrameworkModule\Controller;

use Symfony\Component\HttpFoundation\Response;
use Trident\Component\HttpKernel\Exception\HttpExceptionInterface;
use Trident\Component\HttpKernel\AbstractKernel;
use Trident\Module\FrameworkModule\Controller\Controller;
use Trident\Module\FrameworkModule\Exception\ExceptionTraceParser;

/**
 * Exception Controller
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ExceptionController extends Controller
{
    public function exceptionAction($exception)
    {
        $parser = new ExceptionTraceParser();

        $data = [];
        $data['message']    = $exception->getMessage();
        $data['statusCode'] = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
        $data['trace']      = $parser->getNormalized($exception);
        $data['class']      = [
            'short' => $this->getClassName($exception),
            'long'  => get_class($exception)
        ];

        return $this->render('TridentFrameworkModule:Exception:layout.html.twig', [
            'data'    => $data,
            'version' => AbstractKernel::VERSION
        ]);
    }

    private function getClassName($object)
    {
        if ( ! is_object($object)) {
            throw new \RuntimeException(sprintf('Expected object, but got %s', gettype($object)));
        }

        $name = get_class($object);
        $pos = strrpos($name, '\\');

        return false === $pos ? $name : substr($name, $pos + 1);
    }
}
