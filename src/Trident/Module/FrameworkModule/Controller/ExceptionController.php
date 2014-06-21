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
use Trident\Component\HttpKernel\Exception\ExceptionTraceParser;
use Trident\Component\HttpKernel\Exception\HttpExceptionInterface;
use Trident\Component\HttpKernel\Exception\NormalizedException;
use Trident\Component\HttpKernel\AbstractKernel;
use Trident\Module\FrameworkModule\Controller\Controller;

/**
 * Exception Controller
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ExceptionController extends Controller
{
    public function exceptionAction($exception)
    {
        $exception = new NormalizedException($exception);

        return $this->render('TridentFrameworkModule:Exception:layout.html.twig', [
            'exception' => $exception
        ]);
    }
}
