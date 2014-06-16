<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Templating\Engine;

use Trident\Component\Templating\TemplateReferenceInterface;

/**
 * Template Engine Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface EngineInterface
{
    /**
     * Render template.
     *
     * @param  string $template
     * @param  array  $parameters
     *
     * @return string
     */
    public function render($template, array $parameters = array());

    /**
     * Does this engine support this template?
     *
     * @param  TemplateReferenceInterface $reference
     *
     * @return boolean
     */
    public function isSupported(TemplateReferenceInterface $reference);
}
