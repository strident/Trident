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
use Trident\Component\Caching\Logging\DebugStack;

/**
 * Caching wrapper
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Caching implements DriverInterface
{
    private $driver;
    private $stack;

    /**
     * Set stack.
     *
     * @param DebugStack $stack
     *
     * @return Caching
     */
    public function setStack(DebugStack $stack)
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * Get stack.
     *
     * @return DebugStack
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * Set driver.
     *
     * @param DriverInterface $driver
     *
     * @return Caching
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver.
     *
     * @return DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $expiration = 0)
    {
        $this->driver->set($key, $value, $expiration);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if (isset($this->stack) && $this->driver->has($key)) {
            $this->stack->hit($key);
        }

        return $this->driver->get($key);
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return $this->driver->has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        return $this->driver->remove($key);
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        return $this->driver->flush();
    }
}
