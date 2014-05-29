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

use Trident\Component\Templating\Engine\EngineInterface;

/**
 * Template Engine Wrapper
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
     * @return string
     */
    public function render($template, array $parameters)
    {
        return $this->engine->render($template, $parameters);
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
