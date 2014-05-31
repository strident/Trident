<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Caching\Driver;

/**
 * Cache Driver Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface DriverInterface
{
    /**
     * Set cache value.
     *
     * @param mixed   $key
     * @param mixed   $value
     * @param integer $expiration
     *
     * @return DriverInterface
     */
    public function set($key, $value, $expiration = 0);

    /**
     * Get cache value.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Does the cache contain the given key?
     *
     * @param mixed $key
     *
     * @return boolean
     */
    public function has($key);

    /**
     * Remove the cached value for the given key.
     *
     * @param mixed $key
     *
     * @return DriverInterface
     */
    public function remove($key);

    /**
     * Flush all cache entries.
     *
     * @return DriverInterface
     */
    public function flush();
}
