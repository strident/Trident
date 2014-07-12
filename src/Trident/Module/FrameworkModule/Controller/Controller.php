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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Trident\Component\DependencyInjection\ContainerAware;

/**
 * Base Controller
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Controller extends ContainerAware
{
    /**
     * Render a template
     *
     * @param string   $view
     * @param array    $parameters
     * @param Response $response
     *
     * @return Response
     */
    public function render($view, array $parameters = null, Response $response = null)
    {
        return $this->container->get('templating')->render($view, $parameters, $response);
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route
     * @param mixed  $parameters
     * @param mixed  $referenceType
     *
     * @return string
     */
    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_URL)
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }

    /**
     * Returns a RedirectResponse to the given URL with the given status code
     *
     * @param string  $url
     * @param integer $status
     *
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Return service with given name
     *
     * @param  string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->container->get($name);
    }

    /**
     * True if the container has the given service
     *
     * @param string $name
     *
     * @return boolean
     */
    public function has($name)
    {
        return $this->container->has($name);
    }
}
