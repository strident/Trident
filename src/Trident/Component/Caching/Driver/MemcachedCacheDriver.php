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
        return $this->memcached->set($key, $value, $expiration);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->memcached->get($key);
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return false !== $this->get($key);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        return $this->memcached->delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        return $this->memcached->flush();
    }
}
