<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Bridge\Strident\Aegis\Storage;

use Aegis\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Trident -> Aegis Session Storage Bridge
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class SessionStorage implements StorageInterface
{
    private $session;
    private $sessionKey;

    /**
     * Constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session, $sessionKey)
    {
        $this->session = $session;
        $this->sessionKey = $sessionKey;
    }

    /**
     * {@inheritDoc}
     */
    public function read()
    {
        return $this->session->get($this->sessionKey);
    }

    /**
     * {@inheritDoc}
     */
    public function write($content)
    {
        $this->session->set($this->sessionKey, $content);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return $this->session->has($this->sessionKey);
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->session->remove($this->sessionKey);

        return $this;
    }
}
