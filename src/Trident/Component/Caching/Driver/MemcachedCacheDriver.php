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
 * Memcached Cache Driver
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class MemcachedCacheDriver implements DriverInterface
{
    protected $memcached;

    /**
     * Set \Memcached instance.
     *
     * @param \Memcached $memcached
     */
    public function setMemcached(\Memcached $memcached)
    {
        $this->memcached = $memcached;

        return $this;
    }

    /**
     * Get \Memcached instance.
     *
     * @return \Memcached
     */
    public function getMemcached()
    {
        return $this->memcached;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $expiration = 0)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {

    }
}
