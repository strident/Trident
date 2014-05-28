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

/**
 * Configuration Name Parser Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface ConfigurationNameParserInterface
{
    /**
     * Parse the name of a configuration value and return the value from the
     * configuration data.
     *
     * @param array  $configuration
     * @param string $name
     *
     * @return mixed
     */
    public function parse(array $configuration, $name);
}
