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
                font: 14px/14px "Helvetica Neue", Helvetica, Arial, sans-serif;
                left: 0;
                opacity: 0.3;
                padding: 16px;
                position: fixed;
                right: 0;
            }
            .trt-toolbar:hover {
                opacity: 1;
            }
            .trt-toolbar img {
                vertical-align: top;
                margin: -6px 20px -6px 0;
            }
            .trt-toolbar .trt-logo {
                color: #b48ead;
                display: inline-block;
                margin-right: 20px;
            }
            .trt-toolbar .trt-segments {
                display: inline-block;
                list-style: none;
                margin: 0;
                padding: 0;
            }
            .trt-toolbar .trt-segments li {
                border-right: solid 2px #dfe1e8;
                display: inline-block;
                padding: 0 10px;
            }
            .trt-toolbar .trt-segments li:first-child {
                padding-left: 0;
            }
            .trt-toolbar .trt-segments li:last-child {
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

        $baseName = '';
        if (null !== $segment->getBaseName()) {
            $baseName = "<strong>{$segment->getBaseName()}</strong>: ";
        }

        $indicator = '';
        if (null !== $segment->getBaseIndicator()) {
            $style = '';
            if (null !== $segment->getBaseIndicatorColor()) {
                $style = "style='color: {$segment->getBaseIndicatorColor()};'";
            }

            $indicator = " <span><strong {$style}>{$segment->getBaseIndicator()}</strong></span>";
        }

        // Formatting looks odd here, but it's necessary to output clean HTML
        return <<<EOF
            <li><span>{$baseName}{$segment->getBaseValue()}{$segment->getBaseUnit()}</span>{$indicator}</li>

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
        <img alt="" width="24" height="24" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAUVBMVEUAAAA0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUY0PUZD1886AAAAGnRSTlMAR+9WLai4kwqAzWE5HMHX9wP9EJprdG/liEEY8Z4AAAEfSURBVBgZlcELcpswAEDBBwgk/hDAdt79D1p1ppPY4KbTXfhPIfCztuVnXcffDZA0wcBb6w6FFrCvvHPUEDVCffDGZoSm6xqIblxFDyBG4DByESwLoK6BojRw1riMwDQB42LDxegKzDOwOnLVP8jKkuzRc1U3ZEo21VysRiBpAqIrZ4M3YNQRuDlw1loBQQNQ2XLWGYBCCyDYcbJpAiqtgE03XlUuZIceZIsVr3Y7slZbss6dV6U1WdRIVlvyImhLVmtN1mrg2U17skknsl5vPJt0IJt1Jht04klSR7JSS7JRTXwr1FS0zcOsaYuEWvAt6sNndaeRL2n2Ymka/kj3RT/mJrb3o6/6497GZv7Q5Z74bdg/q7BxtoXqcx/4t1+6iBUVn0jPWwAAAABJRU5ErkJggg==" />
        <ul class="trt-segments">
            {$content}
        </ul>
    </div>
EOF;
    }
}
