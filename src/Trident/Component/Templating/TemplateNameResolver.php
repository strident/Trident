<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Templating;

/**
 * Template Name Parser
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TemplateNameResolver implements TemplateNameParserInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            return $name;
        }


    }
}
