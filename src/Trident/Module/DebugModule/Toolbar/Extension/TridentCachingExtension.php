<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\DebugModule\Toolbar\Extension;

use Trident\Component\Caching\Logging\DebugStack;
use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;

/**
 * Caching Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentCachingExtension extends AbstractExtension
{
    private $stack;

    /**
     * Constructor.
     *
     * @param DebugStack $stack
     */
    public function __construct(DebugStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplateName()
    {
        return 'TridentDebugModule:Toolbar/Extension:caching.html.twig';
    }

    /**
     * Build the extension data.
     *
     * @return array
     */
    public function buildData()
    {
        $this->data = [
            'hits' => count($this->stack->hits)
        ];
    }
}
