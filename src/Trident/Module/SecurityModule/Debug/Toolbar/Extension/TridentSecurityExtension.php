<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\SecurityModule\Debug\Toolbar\Extension;

use Aegis\User\UserInterface;
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
        $token = $this->security->getToken();
        $user  = $token->getUser();

        if ($user instanceof UserInterface) {
            $user = $user->getUsername();
        }

        $tokenClass = is_object($token) ? get_class($token) : 'N/A';
        $userClass  = is_object($token->getUser()) ? get_class($token->getUser()) : 'N/A';

        $this->data = [
            'token'      => $token,
            'tokenClass' => $tokenClass,
            'user'       => $user,
            'userClass'  => $userClass
        ];
    }

    public function getTemplateName()
    {
        return 'TridentDebugModule:Toolbar/Extension:security.html.twig';
    }
}
