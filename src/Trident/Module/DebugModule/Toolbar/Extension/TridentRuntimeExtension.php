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

use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;
use Trident\Component\HttpKernel\AbstractKernel;

/**
 * Application Runtime Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentRuntimeExtension extends AbstractExtension
{
    private $kernel;

    /**
     * Constructor.
     *
     * @param AbstractKernel $kernel
     */
    public function __construct(AbstractKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Build the extension data.
     *
     * @return array
     */
    public function buildData()
    {
        $runtime = (microtime(true) - $this->kernel->getStartTime()) * 1000;

        $this->data = [
            'runtime' => $runtime
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplateName()
    {
        return 'TridentDebugModule:Toolbar/Extension:runtime.html.twig';
    }
}
