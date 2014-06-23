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

use Aegis\User\UserInterface;
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
     * Get application debug mode.
     *
     * @return boolean
     */
    public function getDebug()
    {
        return $this->container['kernel.debug'];
    }

    public function getEnvironment()
    {
        return $this->container['kernel.environment'];
    }

    /**
     * Get the currently authenticated user.
     *
     * @return mixed
     */
    public function getUser()
    {
        if ( ! $this->container->has('security')) {
            return false;
        }

        $security = $this->container->get('security');

        if ( ! $token = $security->getToken()) {
            return false;
        }

        $user = $token->getUser();
        if ( ! $user instanceof UserInterface) {
            return false;
        }

        return $user;
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
