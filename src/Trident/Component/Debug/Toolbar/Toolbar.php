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
    protected $extensions = [];

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

    protected function getStylesheet()
    {
        return <<<EOF
.trident-toolbar {
    background-color: #eff1f5;
    bottom: 0;
    color: #2b303b;
    font: 14px/20px "Helvetica Neue", Helvetica, Arial, sans-serif;
    left: 0;
    position: fixed;
    right: 0;
}

.trident-toolbar:hover {
    opacity: 0.4;
}
EOF;
    }

    protected function getExtensionHtml(ExtensionInterface $extension)
    {
        $segment = $extension->getSegment();

        if ( ! $segment instanceof SegmentInterface) {
            throw new \RuntimeException();
        }

        return <<<EOF
        <li><strong>{$segment->getBaseName()}</strong>: {$segment->getBaseValue()}{$segment->getBaseUnit()}</li>

EOF;
    }

    public function getHtml()
    {
        $content = '';

        foreach ($this->extensions as $extension) {
            $content.= $this->getExtensionHtml($extension);
        }

        $css     = $this->getStylesheet();
        $content = trim($content);

        return <<<EOF
<div class="trident-toolbar">
    <style type="text/css" scoped>
        {$css}
    </style>
    <ul>
        {$content}
    </ul>
</div>
EOF;
    }
}
