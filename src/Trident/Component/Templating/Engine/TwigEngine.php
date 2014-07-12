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

use Trident\Component\Templating\TemplateReferenceInterface;

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
     * @param Twig_LoaderInterface $loader
     * @param array                $options
     */
    public function __construct(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritDoc}
     */
    public function render($template, array $parameters = array())
    {
        return $this->environment->render($template, $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(TemplateReferenceInterface $reference)
    {
        return 'twig' === $reference->get('engine');
    }

    /**
     * Get the Twig environment
     *
     * @return \Twig_Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
}
