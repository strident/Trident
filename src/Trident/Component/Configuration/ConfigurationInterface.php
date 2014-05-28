<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Configuration;

/**
 * Configuration Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface ConfigurationInterface
{
    /**
     * Get a configuration value.
     *
     * @param  string $name
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function get($name, $default = null);
}
