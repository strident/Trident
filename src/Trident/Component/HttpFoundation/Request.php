<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\Request as BaseRequest;

/**
 * Trident Request
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Request extends BaseRequest
{
    /**
     * Generate the URI of the given page.
     *
     * @return string
     */
    public function getPath()
    {
        $rawQuery = $this->query->all();

        $path  = array_shift($rawQuery);
        $query = http_build_query($rawQuery);

        if ('' !== $query) {
            $query = '?'.$query;
        }

        return $path.$query;
    }
}
