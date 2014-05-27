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

use Trident\Component\HttpKernel\Module\AbstractModule;
use Trident\Component\Templating\TemplateReferenceInterface;

/**
 * Template Reference
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TemplateReference implements TemplateReferenceInterface
{
    protected $rootDir;
    protected $parameters;

    /**
     * Constructor.
     *
     * @param string $rootDir
     * @param array  $parameters
     */
    public function __construct($rootDir, array $parameters = null)
    {
        $this->rootDir    = $rootDir;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritDoc}
     */
    public function set($name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get($name)
    {
        return $this->parameters[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return $this->parameters;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()
    {
        $section = str_replace('\\', '/', $this->get('section'));
        $path    = (empty($section) ? '' : $section.'/').$this->get('name').'.'.$this->get('format').'.'.$this->get('engine');

        return $this->rootDir.'/views/'.$path;
    }

    /**
     * {@inheritDoc}
     */
    public function getLogicalName()
    {
        return sprintf('%s:%s:%s.%s.%s', $this->parameters['module'], $this->parameters['section'], $this->parameters['name'], $this->parameters['format'], $this->parameters['engine']);
    }
}
