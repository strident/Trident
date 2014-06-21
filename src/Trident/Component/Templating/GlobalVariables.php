<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Templating;

use Phimple\ContainerInterface;
use Trident\Component\HttpFoundation\Request;
use Trident\Component\HttpKernel\AbstractKernel;

/**
 * Global Variables
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class GlobalVariables
{
    private $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get current request.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->container->get('request');
    }

    /**
     * Get current application version.
     *
     * @return string
     */
    public function getVersion()
    {
        return AbstractKernel::VERSION;
    }
}
