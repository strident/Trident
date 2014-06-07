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
 * Toolbar Segment Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface SegmentInterface
{
    /**
     * Set base name.
     *
     * @param string $name
     *
     * @return SegmentInterface
     */
    public function setBaseName($name);

    /**
     * Get base name.
     *
     * @return string
     */
    public function getBaseName();

    /**
     * Set base value.
     *
     * @param mixed $value
     *
     * @return SegmentInterface
     */
    public function setBaseValue($value);

    /**
     * Get base value.
     *
     * @return mixed
     */
    public function getBaseValue();

    /**
     * Set unit for base value.
     *
     * @param mixed $unit
     */
    public function setBaseUnit($unit);

    /**
     * Get unit for base value.
     *
     * @return mixed
     */
    public function getBaseUnit();
}
