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

use Trident\Component\Configuration\ConfigurationInterface;
use Trident\Component\Configuration\ConfigurationNameParser;

/**
 * Configuration
 *
 * Super simple configuration class. Reads an array and provides are slightly
 * more useful way of accessing it.
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Configuration implements \Countable, ConfigurationInterface
{
    protected $data;
    protected $parser;

    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->parser = new ConfigurationNameParser();
    }

    /**
     * {@inheritDoc}
     */
    public function get($name, $default = null)
    {
        if (null === $value = $this->parser->parse($this->data, $name)) {
            return $default;
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->data, COUNT_RECURSIVE);
    }
}
