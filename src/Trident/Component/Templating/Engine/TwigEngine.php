<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Templating\Engine;

use Trident\Component\Templating\Loader\TwigFileLoader;

/**
 * Twig Template Engine Wrapper
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TwigEngine implements EngineInterface
{
    protected $environment;

    /**
     * Constructor.
     *
     * @param TwigFileLoader $loader
     * @param array          $options
     */
    public function __construct(TwigFileLoader $loader, array $options)
    {
        $this->environment = new \Twig_Environment($loader, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function render($template, array $parameters = null)
    {
        return $this->environment->render($template, $parameters);
    }
}
