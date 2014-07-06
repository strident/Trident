<?php

return function($container) {
    // Parameters
    $container['debug.listener.toolbar_injection.class']   = 'Trident\\Module\\DebugModule\\Toolbar\\Event\\ToolbarInjectionResponseListener';
    $container['debug.toolbar.subscription_manager.class'] = 'Trident\\Module\\DebugModule\\Toolbar\\Event\\ToolbarSubscriptionManager';
    $container['debug.toolbar.class']                      = 'Trident\\Component\\Debug\\Toolbar\\Toolbar';


    // Services
    $container->set('debug.listener.toolbar_injection', function($c) {
        return new $c['debug.listener.toolbar_injection.class']($c->get('templating.engine.delegating'), $c->get('debug.toolbar'));
    });

    $container->set('debug.toolbar.subscription_manager', function($c) {
        return new $c['debug.toolbar.subscription_manager.class']($c->get('debug.toolbar'));
    });

    $container->set('debug.toolbar', function($c) {
        return new $c['debug.toolbar.class']();
    });
};
