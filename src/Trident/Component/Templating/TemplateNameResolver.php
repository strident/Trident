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

use Trident\Component\HttpKernel\HttpKernelInterface;
use Trident\Component\Templating\TemplateNameResolverInterface;
use Trident\Component\Templating\TemplateReference;

/**
 * Template Name Resolver
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TemplateNameResolver implements TemplateNameResolverInterface
{
    /**
     * @var HttpKernelInterface
     */
    protected $kernel;

    /**
     * Constructor.
     *
     * @param HttpKernelInterface $kernel
     */
    public function __construct(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            return $name;
        }

        $name = str_replace(':/', ':', preg_replace('#/{2,}#', '/', strtr($name, '\\', '/')));

        if (false !== strpos($name, '..')) {
            throw new \RuntimeException(sprintf('Template name "%s" contains invalid characters.', $name));
        }

        if ( ! preg_match('/^([^:]*):([^:]*):(.+)\.([^\.]+)\.([^\.]+)$/', $name, $matches)) {
            throw new \InvalidArgumentException(sprintf(
                'Template name "%s" is not valid (format is "module:section:template.format.engine").',
                 $name
            ));
        }

        $parameters = [
            'module'  => $matches[1],
            'section' => $matches[2],
            'name'    => $matches[3],
            'format'  => $matches[4],
            'engine'  => $matches[5]
        ];

        $rootDir = $this->kernel->getRootDir();
        if ($parameters['module']) {
            try {
                $module  = $this->kernel->getModule($matches[1]);
                $rootDir = $module->getRootDir().'/module';
            } catch (\Exception $e) {
                throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid.', $name), 0, $e);
            }
        }

        return new TemplateReference($rootDir, $parameters);
    }
}
