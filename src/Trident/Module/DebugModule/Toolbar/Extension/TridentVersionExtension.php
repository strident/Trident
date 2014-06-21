<?php

namespace Trident\Module\DebugModule\Toolbar\Extension;

use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;
use Trident\Component\HttpKernel\AbstractKernel;

/**
 * Trident Version Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentVersionExtension extends AbstractExtension
{
    /**
     * {@inheritDoc}
     */
    public function getTemplateName()
    {
        return 'TridentDebugModule:Toolbar/Extension:version.html.twig';
    }
}
