<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\FrameworkModule\Debug\Toolbar\Extension;

use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;

/**
 * Memory Usage Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentMemoryUsageExtension extends AbstractExtension
{
    /**
     * Build extension data.
     *
     * @return array
     */
    public function buildData()
    {
        $this->data = [
            'memory'      => memory_get_peak_usage(true),
            'memoryLimit' => $this->convertToBytes(ini_get('memory_limit'))
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplateName()
    {
        return 'TridentDebugModule:Toolbar/Extension:memory-usage.html.twig';
    }

    /**
     * Convert mixed to bytes.
     *
     * @param mixed $memoryLimit
     *
     * @return integer
     */
    private function convertToBytes($memoryLimit)
    {
        if ('-1' === $memoryLimit) {
            return -1;
        }

        $memoryLimit = strtolower($memoryLimit);
        $max = strtolower(ltrim($memoryLimit, '+'));
        if (0 === strpos($max, '0x')) {
            $max = intval($max, 16);
        } elseif (0 === strpos($max, '0')) {
            $max = intval($max, 8);
        } else {
            $max = intval($max);
        }

        switch (substr($memoryLimit, -1)) {
            case 't': $max *= 1024;
            case 'g': $max *= 1024;
            case 'm': $max *= 1024;
            case 'k': $max *= 1024;
        }

        return $max;
    }
}
