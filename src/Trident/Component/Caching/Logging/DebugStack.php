<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Caching\Logging;

/**
 * Caching debug stack.
 *
 * An ultra simple class to just count how many caching hits and misses there
 * have been.
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class DebugStack
{
    public $hits = [];

    /**
     * Record cache hit.
     *
     * @return DebugStack
     */
    public function hit($key)
    {
        $this->hits[count($this->hits) + 1] = $key;

        return $this;
    }
}
