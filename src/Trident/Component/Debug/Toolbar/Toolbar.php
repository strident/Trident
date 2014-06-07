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
.trt-toolbar {
    background-color: #eff1f5;
    bottom: 0;
    color: #2b303b;
    font: 14px/20px "Helvetica Neue", Helvetica, Arial, sans-serif;
    left: 0;
    padding: 12px;
    position: fixed;
    right: 0;
}
.trt-toolbar:hover {
    opacity: 0.4;
}
.trt-logo {
    color: #b48ead;
    display: inline-block;
    margin-right: 20px;
}
.trt-segments {
    display: inline-block;
    list-style: none;
    margin: 0;
    padding: 0;
}
.trt-segments li {
    border-right: solid 2px #dfe1e8;
    display: inline-block;
    padding: 0 10px;
}
.trt-segments li:first-child {
    padding-left: 0;
}
.trt-segments li:last-child {
    border-right: none;
    padding-right: 0;
}
EOF;
    }

    protected function getExtensionHtml(ExtensionInterface $extension)
    {
        $segment = $extension->getSegment();

        if ( ! $segment instanceof SegmentInterface) {
            throw new \RuntimeException();
        }

        // Formatting looks odd here, but it's necessary to output clean HTML
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
<div class="trt-toolbar">
    <style type="text/css" scoped>
        {$css}
    </style>
    <span class="trt-logo">Trident</span>
    <ul class="trt-segments">
        {$content}
    </ul>
</div>
EOF;
    }
}
