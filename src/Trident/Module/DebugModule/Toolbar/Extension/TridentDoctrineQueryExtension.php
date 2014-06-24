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

use Doctrine\DBAL\Logging\DebugStack;
use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;

/**
 * Doctrine Query Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentDoctrineQueryExtension extends AbstractExtension
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
        return 'TridentDebugModule:Toolbar/Extension:doctrine-query.html.twig';
    }

    /**
     * Build the extension data.
     *
     * @return array
     */
    public function buildData()
    {
        $time = 0;
        foreach ($this->stack->queries as $query) {
            $time+= $query['executionMS'];
        }

        $this->data = [
            'count' => count($this->stack->queries),
            'time'  => $time
        ];
    }
}
