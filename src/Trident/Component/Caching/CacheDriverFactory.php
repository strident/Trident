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

use Phimple\ContainerInterface;
use Trident\Component\Caching\Driver\DriverInterface;
use Trident\Component\Configuration\ConfigurationInterface;

/**
 * Cache Driver Factory
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class CacheDriverFactory
{
    protected $configuration;
    protected $container;
    protected $debug;
    protected $debugDriver;
    protected $drivers = [];

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     * @param boolean            $debug
     */
    public function __construct(ContainerInterface $container, ConfigurationInterface $configuration, $debug)
    {
        $this->configuration = $configuration;
        $this->container     = $container;
        $this->debug         = (bool) $debug;
    }

    /**
     * Add a driver name to attempt to load later.
     *
     * @param string $key
     * @param string $name
     *
     * @return DelegatingCacheDriver
     */
    public function addDriver($key, $name)
    {
        $this->drivers[$key] = $name;

        return $this;
    }

    /**
     * Build cache driver.
     *
     * @param  string $name
     *
     * @return DriverInterface
     */
    public function build($name = null)
    {
        if ($this->debug && isset($this->debugDriver)) {
            return $this->resolveDriver($this->debugDriver);
        }

        if (null === $name) {
            $name = $this->configuration->get('caching.default', 'null');
        }

        return $this->resolveDriver($name);
    }

    /**
     * Set the driver for when kernel is in debug mode.
     *
     * @param string $name
     */
    public function setDebugDriver($name)
    {
        $this->debugDriver = $name;

        return $this;
    }

    /**
     * Resolve caching driver from name.
     *
     * @param  string $name
     *
     * @return DriverInterface
     */
    protected function resolveDriver($name)
    {
        if ( ! isset($this->drivers[$name]) || ! $this->container->has($this->drivers[$name])) {
            throw new \RuntimeException(sprintf(
                'Caching driver "%s" does not exist or is not registered as a service.',
                $name
            ));
        }

        $driver = $this->container->get($this->drivers[$name]);

        if ( ! $driver instanceof DriverInterface) {
            throw new \RuntimeException(sprintf(
                'Service "%s" is not a valid caching driver.',
                $this->drivers[$name]
            ));
        }

        return $driver;
    }
}
