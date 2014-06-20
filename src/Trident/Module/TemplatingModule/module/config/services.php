<?php

return function($container) {
    // Parameters
    $container['templating.assetic.asset_manager.class']  = 'Assetic\\AssetManager';
    $container['templating.assetic.factory.class']        = 'Assetic\\Factory\\AssetFactory';
    $container['templating.assetic.filter_manager.class'] = 'Assetic\\FilterManager';
    $container['templating.assetic.twig_extension.class'] = 'Assetic\\Extension\\Twig\\AsseticExtension';
    $container['templating.engine.delegating.class']      = 'Trident\\Component\\Templating\\Engine\\DelegatingEngine';
    $container['templating.engine.twig.class']            = 'Trident\\Component\\Templating\\Engine\\TwigEngine';
    $container['templating.engine_factory.class']         = 'Trident\\Component\\Templating\\Engine\\EngineFactory';
    $container['templating.file_loader.twig.class']       = 'Trident\\Component\\Templating\\Loader\\TwigFileLoader';
    $container['templating.name_resolver.class']          = 'Trident\\Component\\Templating\\TemplateNameResolver';
    $container['templating.class']                        = 'Trident\\Component\\Templating\\Templating';


    // Services
    $container->set('templating.assetic.asset_manager', function($c) {
        return new $c['templating.assetic.asset_manager.class']();
    });

    $container->set('templating.assetic.factory', function($c) {
        $kernel = $c->get('kernel');
        $assets = $kernel->getRootDir().'/../public';

        $factory = new $c['templating.assetic.factory.class']($assets);
        $factory->setAssetManager($c->get('templating.assetic.asset_manager'));
        $factory->setFilterManager($c->get('templating.assetic.filter_manager'));

        return $factory;
    });

    $container->set('templating.assetic.filter_manager', function($c) {
        return new $c['templating.assetic.filter_manager.class']();
    });

    $container->set('templating.engine.delegating', function($c) {
        $resolver = $c->get('templating.name_resolver');

        $engine = new $c['templating.engine.delegating.class']($c, $resolver);
        $engine->addEngine('templating.engine.twig');

        return $engine;
    });

    $container->set('templating.engine.twig', function($c) {
        return new $c['templating.engine.twig.class']($c->get('templating.file_loader.twig'), [
            'cache'            => $c['kernel.debug'] ? false : $c->get('configuration')->get('twig.cache_dir'),
            'debug'            => $c['kernel.debug'],
            'strict_variables' => true
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
        $templating = new $c['templating.class']();
        $templating->setEngine($c->get('templating.engine.delegating'));

        return $templating;
    });


    // Extensions
    $container->extend('templating.engine.twig', function($twig, $c) {
        $environment = $twig->getEnvironment();
        $environment->addExtension(new $c['templating.assetic.twig_extension.class']($c->get('templating.assetic.factory')));

        return $twig;
    });
};
