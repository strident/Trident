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

    /**
     * Set stack.
     *
     * @param DebugStack $stack
     *
     * @return TridentDoctrineQueryExtension
     */
    public function setStack(DebugStack $stack)
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * Get stack.
     *
     * @return DebugStack
     */
    public function getStack()
    {
        return $this->stack;
    }
}
