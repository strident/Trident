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

/**
 * Container Aware Interface
 */
interface ContainerAwareInterface
{
    public function setContainer(Container $container);
}
