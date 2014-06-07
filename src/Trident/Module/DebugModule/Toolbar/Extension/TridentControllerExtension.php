<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\DebugModule\Toolbar\Extension;

use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;
use Trident\Component\Debug\Toolbar\Segment;

/**
 * Memory Usage Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentControllerExtension extends AbstractExtension
{
    protected $action;
    protected $controller;

    /**
     * {@inheritDoc}
     */
    public function getSegment()
    {
        $this->decorateSegment();

        return parent::getSegment();
    }

    /**
     * Decorate this extensions Segment.
     *
     * @return Segment
     */
    protected function decorateSegment()
    {
        $this->segment->setBaseName('Controller');
        $this->segment->setBaseValue("{$this->controller}::{$this->action}");

        return $this->segment;
    }

    /**
     * Set action.
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set controller.
     *
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get controller.
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }
}
