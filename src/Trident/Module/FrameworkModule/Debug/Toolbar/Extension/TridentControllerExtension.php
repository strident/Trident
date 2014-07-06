<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\FrameworkModule\Debug\Toolbar\Extension;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;
use Trident\Component\HttpKernel\Event\FilterControllerEvent;
use Trident\Component\HttpKernel\Event\FilterResponseEvent;
use Trident\Component\HttpKernel\HttpKernelInterface;
use Trident\Component\HttpKernel\KernelEvents;

/**
 * Controller Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentControllerExtension extends AbstractExtension implements EventSubscriberInterface
{
    private $action;
    private $controller;
    private $statusCode;

    /**
     * {@inheritDoc}
     */
    public function getTemplateName()
    {
        return 'TridentDebugModule:Toolbar/Extension:controller.html.twig';
    }

    /**
     * Build the extension data.
     *
     * @return array
     */
    public function buildData()
    {
        $this->data = [
            'action'     => $this->action,
            'controller' => $this->controller,
            'statusCode' => $this->statusCode,
        ];
    }

    /**
     * Set action.
     *
     * @param string $action
     *
     * @return TridentControllerExtension
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
     *
     * @return TridentControllerExtension
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

    /**
     * Set status code.
     *
     * @param integer $statusCode
     *
     * @return TridentControllerExtension
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get status code.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['collectControllerData', 0],
            KernelEvents::RESPONSE   => ['collectResponseData', 0]
        ];
    }

    /**
     * Collect data from controller.
     *
     * @param FilterResponseEvent $event
     */
    public function collectControllerData(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::SUB_REQUEST === $event->getRequestType()) {
            return;
        }

        $controller = $this->getClassName($event->getController()[0]);
        $action     = $event->getController()[1];

        $this->setController($controller);
        $this->setAction($action);
    }

    /**
     * Collect data from response.
     *
     * @param FilterResponseEvent $event
     */
    public function collectResponseData(FilterResponseEvent $event)
    {
        $this->setStatusCode($event->getResponse()->getStatusCode());
    }

    /**
     * {@inheritDoc}
     */
    protected function getClassName($object)
    {
        $name = get_class($object);
        $pos = strrpos($name, '\\');

        return false === $pos
            ? $name
            : substr($name, $pos + 1);
    }
}
