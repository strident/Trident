<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Templating\Loader;

use Trident\Component\Templating\TemplateNameResolverInterface;

/**
 * Twig File Loader
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TwigFileLoader implements \Twig_LoaderInterface
{
    protected $resolver;

    /**
     * Constructor.
     *
     * @param TemplateNameResolverInterface $resolver
     */
    public function __construct(TemplateNameResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritDoc}
     */
    public function getSource($name)
    {
        $path = $this->resolver->resolve($name)->getPath();

        if ( ! file_exists($path)) {
            throw new \Exception(sprintf('Templat "%s" not found.', $name));
        }

        return file_get_contents($path);
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheKey($name)
    {
        return $this->resolver->resolve($name)->getPath();
    }

    /**
     * {@inheritDoc}
     */
    public function isFresh($name, $time)
    {
        $path = $this->resolver->resolve($name)->getPath();

        if (file_exists($path)) {
            return filemtime($path) <= $time;
        }

        return false;
    }
}
