<?php

return function($container) {
    // Parameters
    $container['templating.engine.twig.extension.assetic.class'] = 'Assetic\\Extension\\Twig\\AsseticExtension';
    $container['templating.engine.twig.extension.asset.class']   = 'Trident\Module\TemplatingModule\Twig\Extension\AssetExtension';
    $container['templating.engine.twig.extension.url.class']     = 'Trident\Module\TemplatingModule\Twig\Extension\UrlExtension';


    // Extensions
    $container->extend('templating.engine.twig.environment', function($environment, $c) {
        $environment->addExtension(new $c['templating.engine.twig.extension.assetic.class']($c->get('templating.assetic.factory')));
        $environment->addExtension(new $c['templating.engine.twig.extension.asset.class']($c->get('request')));
        $environment->addExtension(new $c['templating.engine.twig.extension.url.class']($c->get('router')));
        $environment->addExtension($c->get('templating.engine.twig.forms.form_extension'));

        // Add global variables
        $environment->addGlobal('app', $c->get('templating.global_variables'));

        return $environment;
    });
};
