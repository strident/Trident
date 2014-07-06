<?php

return function($container) {
    // Parameters
    $container['templating.engine.twig.extension.assetic.class'] = 'Assetic\\Extension\\Twig\\AsseticExtension';
    $container['templating.engine.twig.extension.asset.class']   = 'Trident\Module\TemplatingModule\Twig\Extension\AssetExtension';


    // Extensions
    $container->extend('templating.engine.twig', function($twig, $c) {
        $environment = $twig->getEnvironment();
        $environment->addExtension(new $c['templating.engine.twig.extension.assetic.class']($c->get('templating.assetic.factory')));
        $environment->addExtension(new $c['templating.engine.twig.extension.asset.class']($c->get('request')));

        // Add global variables
        $environment->addGlobal('app', $c->get('templating.global_variables'));

        return $twig;
    });
};
