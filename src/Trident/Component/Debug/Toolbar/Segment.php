<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Debug\Toolbar;

/**
 * Toolbar Segment
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Segment implements SegmentInterface
{
    protected $baseName;
    protected $baseValue;
    protected $baseUnit;

    /**
     * {@inheritDoc}
     */
    public function setBaseName($name)
    {
        $this->baseName = $name;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseName()
    {
        return $this->baseName;
    }

    /**
     * {@inheritDoc}
     */
    public function setBaseValue($value)
    {
        $this->baseValue = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseValue()
    {
        return $this->baseValue;
    }

    /**
     * {@inheritDoc}
     */
    public function setBaseUnit($unit)
    {
        $this->baseUnit = $unit;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseUnit()
    {
        return $this->baseUnit;
    }
}
