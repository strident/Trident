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
use Trident\Module\FrameworkModule\Controller\Controller;

/**
 * Welcome Controller
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class WelcomeController extends Controller
{
    public function indexAction()
    {
        return new Response(<<<EOF
<!DOCTYPE html>
<html>
<head>
    <title>Trident</title>
</head>
<body>
    <p>Welcome to Trident.</p>
</body>
</html>
EOF
);
    }
}
