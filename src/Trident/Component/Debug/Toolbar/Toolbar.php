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

use Trident\Component\Debug\Toolbar\Extension\ExtensionInterface;

/**
 * Debug Toolbar
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Toolbar
{
    private $extensions = [];

    /**
     * Add an extension.
     *
     * @param ExtensionInterface $extension
     *
     * @return Toolbar
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions[$extension->getName()] = $extension;

        return $this;
    }

    /**
     * Clear all extensions.
     *
     * @return Toolbar
     */
    public function clearExtensions()
    {
        $this->extensions = [];

        return $this;
    }

    /**
     * Get extension.
     *
     * @param string $name
     *
     * @return ExtensionInterface
     */
    public function getExtension($name)
    {
        return isset($this->extensions[$name])
            ? $this->extensions[$name]
            : null;
    }

    /**
     * Get all extensions.
     *
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Remove an extension.
     *
     * @param string $name
     *
     * @return Toolbar
     */
    public function removeExtension($name)
    {
        if (isset($this->extensions[$name])) {
            unset($this->extensions[$name]);
        }

        return $this;
    }
}
