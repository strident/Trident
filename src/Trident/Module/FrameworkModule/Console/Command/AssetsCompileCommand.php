<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\FrameworkModule\Console\Command;

use Assetic\AssetWriter;
use Assetic\Extension\Twig\TwigFormulaLoader;
use Assetic\Extension\Twig\TwigResource;
use Assetic\Factory\LazyAssetManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Module asset compile command
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class AssetsCompileCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    public function configure()
    {
        // Meta
        $this->setName('assets:compile');
        $this->setDescription('Compile assets installed that are used in Twig views.');
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $kernel    = $this->getApplication()->getKernel();
        $container = $kernel->getContainer();
        $modules   = $kernel->getModules();
        $publicDir = $kernel->getRootDir().'/../public';

        // Print header
        $output->writeln(sprintf('Compiling assets for <info>%s</info> modules.', count($modules)));
        $output->writeln(sprintf('The current environment is <comment>"%s"</comment>.', $container['kernel.environment']));
        $output->writeln(sprintf('Debug mode is <comment>%s</comment>.', $container['kernel.debug'] ? 'on' : 'off'));
        $output->writeln('');

        // Find all templates in the application
        $templates = $this->findTemplates($modules, $output);

        // Set up Assetic and Twig for compilation
        $af   = $container->get('templating.assetic.factory');
        $twig = $container->get('templating.engine.twig')->getEnvironment();
        $twig->setLoader(new \Twig_Loader_Filesystem('/'));
        $twig->addExtension(new $container['templating.assetic.twig_extension.class']($af));

        $am = new LazyAssetManager($af);
        $am->setLoader('twig', new TwigFormulaLoader($twig));

        foreach ($templates as $template) {
            $resource = new TwigResource($twig->getLoader(), $template);
            $am->addResource($resource, 'twig');
        }

        $output->writeln(sprintf(
            '<comment>Compiling %s asset(s) from %s templates...</comment>',
            count($am->getNames()),
            count($templates)
        ));

        try {
            $writer = new AssetWriter($publicDir);
            $writer->writeManagerAssets($am);

            $output->writeln(sprintf('<info>Output written to: %s</info>', $publicDir));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Failed to write output to: %s</error>', $publicDir));
        }
    }

    /**
     * Find templates. Outputs status.
     *
     * @param array           $modules
     * @param OutputInterface $output
     *
     * @return array
     */
    protected function findTemplates(array $modules, OutputInterface $output)
    {
        $filesystem = new FileSystem();

        $templates = [];

        foreach ($modules as $module) {
            $output->write(sprintf(
                'Finding templates in "%s" module', $module->getName()
            ));

            $viewsDir = $module->getRootDir().'/module/views';

            try {
                if ($filesystem->exists($viewsDir)) {
                    $finder = new Finder();
                    $finder->files()->in($viewsDir)->name('*.html.twig');

                    foreach ($finder as $file) {
                        $templates[] = $file->getPathName();
                    }

                    $output->writeln(sprintf('... <info>%s Found</info>', count($finder)));
                } else {
                    $output->writeln('... <comment>N/A</comment>');
                }
            } catch (\Exception $e) {
                $output->writeln('... <error>Error</error>');
            }
        }

        $output->writeln('');

        return $templates;
    }
}
