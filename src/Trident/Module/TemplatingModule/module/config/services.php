<?php

return function($container) {
    // Parameters
    $container['templating_name_resolver.class'] = 'Trident\\Component\\Templating\\TemplateNameResolver';


    // Services
    $container->set('templating_name_resolver', function($c) {
        return new $c['templating_name_resolver.class']($c->get('kernel'));
    });

    $container->set('templating', function($c) {
        return new Twig_Environment(null, []);
    });
};
