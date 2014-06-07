<?php

namespace Trident\Module\DebugModule\Toolbar\Extension;

use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;
use Trident\Component\Debug\Toolbar\Segment;
use Trident\Component\HttpKernel\AbstractKernel;

/**
 * Trident Version Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentVersionExtension extends AbstractExtension
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
        $this->segment->setBaseName('Version');
        $this->segment->setBaseValue('v'.AbstractKernel::VERSION);

        return $this->segment;
    }
}