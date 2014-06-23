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

use Aegis\Aegis;
use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;

/**
 * Application Security Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentSecurityExtension extends AbstractExtension
{
    private $security;

    /**
     * Constructor.
     *
     * @param Aegis $security
     */
    public function __construct(Aegis $security)
    {
        $this->security = $security;
    }

    /**
     * Build the extension data.
     *
     * @return array
     */
    public function buildData()
    {
        $this->data = [
            'token'      => $this->security->getToken(),
            'tokenClass' => get_class($this->security->getToken()),
            'userClass'  => get_class($this->security->getToken()->getUser())
        ];
    }

    public function getTemplateName()
    {
        return 'TridentDebugModule:Toolbar/Extension:security.html.twig';
    }
}
