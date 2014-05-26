<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\DependencyInjection;

use Phimple\Container;
use Trident\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Container Aware
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
abstract class ContainerAware implements ContainerAwareInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }
}
