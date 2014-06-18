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

/**
 * Exception Controller
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ExceptionController
{
    public function exceptionAction()
    {
        $html = <<<EOF
<!DOCTYPE html>
<html>
    <head>
        <title>An error has occurred.</title>
    </head>
    <body>
        <p>An error has occurred.</p>
    </body>
</html>
EOF;

        return new Response($html, 500);
    }
}
