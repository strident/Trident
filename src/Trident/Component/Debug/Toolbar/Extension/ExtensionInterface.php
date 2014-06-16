<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Debug\Toolbar\Extension;

use Trident\Component\Debug\Toolbar\SegmentInterface;

/**
 * Toolbar Extension Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface ExtensionInterface
{
    /**
     * Get the extension data.
     *
     * @return array
     */
    public function getData();

    /**
     * Get the extension name.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the extension template name.
     *
     * @return string
     */
    public function getTemplateName();
}
