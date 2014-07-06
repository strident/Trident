<?php

return function($container) {
    // Parameters
    $container['debug.toolbar.extension.caching.class']        = 'Trident\\Module\\FrameworkModule\\Debug\\Toolbar\\Extension\\TridentCachingExtension';
    $container['debug.toolbar.extension.controller.class']     = 'Trident\\Module\\FrameworkModule\\Debug\\Toolbar\\Extension\\TridentControllerExtension';
    $container['debug.toolbar.extension.memory_usage.class']   = 'Trident\\Module\\FrameworkModule\\Debug\\Toolbar\\Extension\\TridentMemoryUsageExtension';
    $container['debug.toolbar.extension.runtime.class']        = 'Trident\\Module\\FrameworkModule\\Debug\\Toolbar\\Extension\\TridentRuntimeExtension';
    $container['debug.toolbar.extension.version.class']        = 'Trident\\Module\\FrameworkModule\\Debug\\Toolbar\\Extension\\TridentVersionExtension';


    // Services
    $container->set('debug.toolbar.extension.caching', function($c) {
        return new $c['debug.toolbar.extension.caching.class']($c->get('caching')->getStack());
    });

    $container->set('debug.toolbar.extension.controller', function($c) {
        return new $c['debug.toolbar.extension.controller.class']();
    });

    $container->set('debug.toolbar.extension.memory_usage', function($c) {
        return new $c['debug.toolbar.extension.memory_usage.class']();
    });

    $container->set('debug.toolbar.extension.runtime', function($c) {
        return new $c['debug.toolbar.extension.runtime.class']($c->get('kernel'));
    });

    $container->set('debug.toolbar.extension.version', function($c) {
        return new $c['debug.toolbar.extension.version.class']($c->get('kernel'));
    });
};
