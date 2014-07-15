<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\TemplatingModule\Twig\Extension;

use Symfony\Component\Routing\Router;

/**
 * Trident Url Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class UrlExtension extends \Twig_Extension
{
    private $router;

    /**
     * Constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            'url' => new \Twig_Function_Function([$this, 'url']),
        ];
    }

    /**
     * Get the absolute URL of an asset.
     *
     * @param string $route
     * @param array  $params
     *
     * @return string
     */
    public function url($route, array $params = array())
    {
        return $this->router->generate($route, $params, true);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'tridenturlextension';
    }
}
