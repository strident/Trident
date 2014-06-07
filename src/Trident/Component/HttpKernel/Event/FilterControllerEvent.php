<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpKernel\Event;

use Trident\Component\HttpKernel\Event\KernelEvent;

/**
 *
 */
class FilterControllerEvent extends KernelEvent
{
    protected $arguments;
    protected $controller;

    /**
     * Set controller.
     *
     * @param object $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get controller.
     *
     * @return object
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set arguments.
     *
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Get arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
