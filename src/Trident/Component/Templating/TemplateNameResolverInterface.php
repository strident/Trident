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
 * Template Name Parser Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface TemplateNameResolverInterface
{
    /**
     * Convert template 'name' string to a TemplateReferenceInterface instance.
     *
     * @param  string $name
     *
     * @return TemplateReferenceInterface
     */
    public function resolve($name);
}
