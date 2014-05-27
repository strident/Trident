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
 * Template Reference Interface
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
interface TemplateReferenceInterface
{
    public function set($name, $value);
    public function get($name);
    public function all();
    public function getPath();
    public function getLogicalName();
}
