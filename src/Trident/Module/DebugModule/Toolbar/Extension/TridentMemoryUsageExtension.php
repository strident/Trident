<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\DebugModule\Toolbar\Extension;

use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;
use Trident\Component\Debug\Toolbar\Segment;

/**
 * Memory Usage Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentMemoryUsageExtension extends AbstractExtension
{
    /**
     * {@inheritDoc}
     */
    public function getSegment()
    {
        $this->decorateSegment();

        return parent::getSegment();
    }

    /**
     * Decorate this extensions Segment.
     *
     * @return Segment
     */
    protected function decorateSegment()
    {
        $memory = memory_get_peak_usage() / 1048576;

        $this->segment->setBaseName('Memory');
        $this->segment->setBaseValue(round($memory, 2));
        $this->segment->setBaseUnit('MiB');

        return $this->segment;
    }
}
