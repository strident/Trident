<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Caching;

use Trident\Component\Caching\Driver\DriverInterface;

/**
 * Caching Proxy
 *
 * Requests to resources in other components can be passed through this to be
 * served from the cache, or cached with an easy, consistent, and flexible
 * interface.
 *
 * Note: This class is not an interface to the cache, it is only used for
 * retrieving information from the cache.
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class CachingProxy
{
    protected $driver;

    /**
     * Set cache driver.
     *
     * @param DriverInterface $driver
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get cache driver.
     *
     * @return DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Get a value, potentially from cache.
     *
     * @param string  $key
     * @param Closure $data
     *
     * @return mixed
     */
    public function proxy($key, \Closure $data, $expiration = 0)
    {
        if ($this->driver->has($key)) {
            return $this->driver->get($key);
        }

        // Resolve the 'real' data source
        $data = $data();

        // Value wasn't cached, so cache it for next time
        $this->driver->set($key, $data, $expiration);

        return $data;
    }
}
