<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\Templating\Engine;

use Phimple\ContainerInterface;
use Trident\Component\Templating\Engine\EngineInterface;
use Trident\Component\Templating\TemplateNameResolverInterface;
use Trident\Component\Templating\TemplateReferenceInterface;

/**
 * Delegating Engine
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class DelegatingEngine implements EngineInterface
{
    protected $container;
    protected $engines = [];
    protected $resolver;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, TemplateNameResolverInterface $resolver)
    {
        $this->container = $container;
        $this->resolver = $resolver;
    }

    /**
     * Add template engine service name
     *
     * @param string $name
     */
    public function addEngine($name)
    {
        $this->engines[] = $name;
    }

    /**
     * Get the given template engine
     *
     * @param string $template
     *
     * @return EngineInterface
     */
    public function getEngine($template)
    {
        return $this->resolveEngine($template);
    }

    /**
     * Resolve template engine from name.
     *
     * @param string $template
     *
     * @return EngineInterface
     */
    protected function resolveEngine($template)
    {
        $reference     = $this->resolver->resolve($template);
        $engine        = $reference->get('engine');
        $engineService = 'templating.engine.'.$engine;

        if ( ! $this->container->has($engineService)) {
            throw new \RuntimeException(sprintf(
                'Template engine "%s" does not exist or is not registered as a service.',
                $engineService
            ));
        }

        $engine = $this->container->get($engineService);

        if ( ! $engine instanceof EngineInterface) {
            throw new \RuntimeException(sprintf(
                'Service "%s" is not a valid template engine.',
                $name
            ));
        }

        return $engine;
    }

    /**
     * {@inheritDoc}
     */
    public function render($template, array $parameters = null)
    {
        $engine = $this->resolveEngine($template);

        return $engine->render($template, $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported(TemplateReferenceInterface $reference)
    {
        $engine = $this->resolveEngine($reference->getLogicalName());

        return $engine->isSupported($reference);
    }
}
