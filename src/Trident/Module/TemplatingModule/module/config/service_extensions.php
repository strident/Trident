<?php

return function($container) {
    // Extensions
    $container->extend('templating.engine.twig', function($twig, $c) {
        $environment = $twig->getEnvironment();
        $environment->addExtension(new $c['templating.assetic.twig_extension.class']($c->get('templating.assetic.factory')));

        // Add global variables
        $environment->addGlobal('app', $c->get('templating.global_variables'));

        return $twig;
    });
};
