<?php

return function($container) {
    // Parameters
    $container['templating.class'] = 'Trident\\Component\\HttpKernel\\Controller\\ControllerResolver';


    // Services
    $container->set('templating', function($c) {
        return new $c['templating.class']();
    });
};
