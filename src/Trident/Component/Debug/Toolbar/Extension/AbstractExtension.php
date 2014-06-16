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

use Trident\Component\Debug\Toolbar\Extension\ExtensionInterface;
use Trident\Component\Debug\Toolbar\Segment;

/**
 * Abstract Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
abstract class AbstractExtension implements ExtensionInterface
{
    protected $name;
    protected $data = [];

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    final public function getName()
    {
        if (null !== $this->name) {
            return $this->name;
        }

        $name = get_class($this);
        $pos = strrpos($name, '\\');

        return $this->name = false === $pos ? $name : substr($name, $pos + 1);
    }

    /**
     * {@inheritDoc}
     */
    abstract public function getTemplateName();
}
