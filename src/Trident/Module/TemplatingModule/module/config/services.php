<?php

return function($container) {
    // Parameters


    // Services
    $container->set('templating', function($c) {
        return new Twig_Environment(null, []);
    });
};
