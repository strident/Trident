<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Templating;

use Symfony\Component\HttpFoundation\Response;
use Trident\Component\Templating\Engine\EngineInterface;

/**
 * Template Engine Wrapper
 *
 * Transform a template into a response, or modify a response that's been
 * prepared already.
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class Templating
{
    protected $engine;

    /**
     * Render a template with the template engine
     *
     * @param  string $template
     * @param  array  $parameters
     *
     * @return Response
     */
    public function render($template, array $parameters = null, Response $response = null)
    {
        if ( ! $response instanceof Response) {
            $response = new Response();
        }

        $response->setContent($this->engine->render($template, $parameters));

        return $response;
    }

    /**
     * Set template engine
     *
     * @param EngineInterface $engine
     *
     * @return Templating
     */
    public function setEngine(EngineInterface $engine)
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * Get template engine
     *
     * @return EngineInterface
     */
    public function getEngine()
    {
        return $this->engine;
    }
}
