<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpKernel\Controller;

use Phimple\Container;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Trident\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Controller Resolver
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ControllerResolver
{
    protected $container;

    /**
     * Constructor.
     *
     * @param Container       $container
     * @param LoggerInterface $logger
     */
    public function __construct(Container $container, LoggerInterface $logger = null)
    {
        $this->container = $container;
    }

    /**
     * Get a callable controller
     *
     * @param  Request $request
     * @param  array   $matched
     * @return callable
     */
    public function getController(Request $request, array $matched)
    {
        if (null !== $request->attributes->get('_controller')) {
            return $this->createController($request->attributes->get('_controller'));
        }

        if ( ! $controller = $matched['_controller']) {
            throw new \InvalidArgumentException('Unable to look for controller as the "_controller" parameter is missing');
        }

        $callable = $this->createController($controller);

        if ( ! is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf('Controller "%s" for URI "%s" is not callable.', $controller, $request));
        }

        return $callable;
    }

    /**
     * Creates the callable from the controller string
     *
     * @param  string $controller
     * @return callable
     */
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = new $class();
        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return array($controller, $method);
    }

    /**
     * Get controller action arguments
     *
     * @param  Request $request
     * @param  array   $controller
     * @param  array   $matched
     * @return array
     */
    public function getArguments(Request $request, array $controller, array $matched)
    {
        $reflection = new \ReflectionMethod($controller[0], $controller[1]);
        $arguments  = [];

        foreach ($reflection->getParameters() as $param) {
            if (array_key_exists($param->name, $matched)) {
                $arguments[] = $matched[$param->name];
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                $controller = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (either because there is no default value, or because there is a non-optional argument after this one).', $controller, $param->name));
            }
        }

        return $arguments;
    }
}
