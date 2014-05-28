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

use Trident\Component\Configuration\ConfigurationNameParserInterface;

/**
 * Configuration Name Parser
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class ConfigurationNameParser implements ConfigurationNameParserInterface
{
    /**
     * {@inheritDoc}
     */
    public function parse(array $configuration, $name)
    {
        // Normalise name
        $name = preg_replace("/[^\w\d.]/ui", '', $name);

        if (false !== strpos($name, '..')) {
            throw new \RuntimeException(sprintf(
                'Configuration key name "%s" contains invalid characters.',
                $name
            ));
        }

        $levels = explode('.', $name);
        $resolved = null;

        if (isset($configuration[$levels[0]])) {
            $resolved = $configuration[array_shift($levels)];

            foreach ($levels as $level) {
                if ( ! isset($resolved[$level])) {
                    $resolved = null;
                    break;
                }

                $resolved = $resolved[array_shift($levels)];
            }
        }

        return $resolved;
    }
}
