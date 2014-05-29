<?php

return function($container) {
    // Parameters
    $container['templating.engine.twig.class']      = 'Trident\\Component\\Templating\\Engine\\TwigEngine';
    $container['templating.engine_factory.class']   = 'Trident\\Component\\Templating\\Engine\\EngineFactory';
    $container['templating.file_loader.twig.class'] = 'Trident\\Component\\Templating\\Loader\\TwigFileLoader';
    $container['templating.name_resolver.class']    = 'Trident\\Component\\Templating\\TemplateNameResolver';
    $container['templating.class']                  = 'Trident\\Component\\Templating\\Templating';


    // Services
    $container->set('templating.engine.twig', function($c) {
        return new $c['templating.engine.twig.class']($c->get('templating.file_loader.twig'), [
            'cache' => $c->get('configuration')->get('twig.cache_dir'),
            'debug' => $c['kernel.debug']
        ]);
    });

    $container->set('templating.engine_factory', function($c) {
        return new $c['templating.engine_factory.class']();
    });

    $container->set('templating.file_loader.twig', function($c) {
        $resolver = $c->get('templating.name_resolver');

        return new $c['templating.file_loader.twig.class']($resolver);
    });

    $container->set('templating.name_resolver', function($c) {
        return new $c['templating.name_resolver.class']($c->get('kernel'));
    });

    $container->set('templating', function($c) {
        $configuration = $c->get('configuration');
        $engineService = 'templating.engine.'.$configuration->get('templating.engine', 'twig');

        if ( ! $c->has($engineService)) {
            throw new \RuntimeException(sprintf(
                'Template engine "%s" does not exist or is not registered as a service.',
                $configuration->get('templating.engine', 'twig')
            ));
        }

        $templating = new $c['templating.class']();
        $templating->setEngine($c->get($engineService));

        return $templating;
    });
};
