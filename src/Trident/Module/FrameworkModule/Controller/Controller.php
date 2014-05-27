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

use Phalcon\Http\Response;
use Trident\Component\DependencyInjection\ContainerAware;

/**
 * Base Controller
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Controller extends ContainerAware
{
    public function render($view, array $parameters = null, Response $repsonse = null)
    {
        // return $this->container->get('templating');
    }
}
