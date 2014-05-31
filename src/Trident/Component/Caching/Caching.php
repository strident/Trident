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
 * Caching
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Caching
{
    protected $driver;

    /**
     * Set driver.
     *
     * @param DriverInterface $driver
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
        // @todo: add something to check if the driver is set

        return $this->driver;
    }
}
