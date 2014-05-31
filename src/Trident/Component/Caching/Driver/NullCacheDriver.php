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

use Trident\Component\Caching\Driver\DriverInterface;

/**
 * Null Cache Driver
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class NullCacheDriver implements DriverInterface
{
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