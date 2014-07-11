<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\TemplatingModule\Twig\Extension;

use Trident\Component\HttpFoundation\Request;

/**
 * Trident Asset Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class AssetExtension extends \Twig_Extension
{
    private $request;

    /**
     * Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            'asset' => new \Twig_Function_Function([$this, 'asset']),
        ];
    }

    /**
     * Get the absolute URL of an asset.
     *
     * @return string
     */
    public function asset($asset, $version = null)
    {
        $host = $this->request->getHost();
        $port = $this->request->getPort();
        $path = $this->request->getBasePath();

        if ('/' !== substr($asset, 1)) {
            $asset = '/'.$asset;
        }

        if (80 === $port) {
            $port = '';
        } else {
            $port = ':'.$port;
        }

        return '//'.$host.$port.$path.$asset;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'tridentassetextension';
    }
}
