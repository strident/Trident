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
    protected $baseIndicator;
    protected $baseIndicatorColor;

    // Taken from Chris Kempson's base16 (ocean)
    // http://chriskempson.github.io/base16/#ocean
    const RED    = '#bf616a';
    const ORANGE = '#d08770';
    const YELLOW = '#ebcb8b';
    const GREEN  = '#a3be8c';
    const CYAN   = '#96b5b4';
    const BLUE   = '#8fa1b3';
    const PURPLE = '#b48ead';
    const BROWN  = '#ab7967';

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

    /**
     * {@inheritDoc}
     */
    public function setBaseIndicator($indicator)
    {
        $this->baseIndicator = $indicator;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseIndicator()
    {
        return $this->baseIndicator;
    }

    /**
     * {@inheritDoc}
     */
    public function setBaseIndicatorColor($indicatorColor)
    {
        $this->baseIndicatorColor = $indicatorColor;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseIndicatorColor()
    {
        return $this->baseIndicatorColor;
    }
}
