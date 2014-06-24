<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Parameters
    $container['debug.listener.toolbar_injection.class']       = 'Trident\\Module\\DebugModule\\Toolbar\\Event\\ToolbarInjectionResponseListener';
    $container['debug.toolbar.extension.caching.class']        = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentCachingExtension';
    $container['debug.toolbar.extension.controller.class']     = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentControllerExtension';
    $container['debug.toolbar.extension.doctrine_query.class'] = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentDoctrineQueryExtension';
    $container['debug.toolbar.extension.memory_usage.class']   = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentMemoryUsageExtension';
    $container['debug.toolbar.extension.runtime.class']        = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentRuntimeExtension';
    $container['debug.toolbar.extension.security.class']       = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentSecurityExtension';
    $container['debug.toolbar.extension.version.class']        = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentVersionExtension';
    $container['debug.toolbar.subscription_manager.class']     = 'Trident\\Module\\DebugModule\\Toolbar\\Event\\ToolbarSubscriptionManager';
    $container['debug.toolbar.class']                          = 'Trident\\Component\\Debug\\Toolbar\\Toolbar';


    // Services
    $container->set('debug.listener.toolbar_injection', function($c) {
        return new $c['debug.listener.toolbar_injection.class']($c->get('templating.engine.delegating'), $c->get('debug.toolbar'));
    });

    $container->set('debug.toolbar.extension.caching', function($c) {
        return new $c['debug.toolbar.extension.caching.class']($c->get('caching')->getStack());
    });

    $container->set('debug.toolbar.extension.controller', function($c) {
        return new $c['debug.toolbar.extension.controller.class']();
    });

    $container->set('debug.toolbar.extension.doctrine_query', function($c) {
        return new $c['debug.toolbar.extension.doctrine_query.class']($c->get('doctrine.orm.sql_logger'));
    });

    $container->set('debug.toolbar.extension.memory_usage', function($c) {
        return new $c['debug.toolbar.extension.memory_usage.class']();
    });

    $container->set('debug.toolbar.extension.runtime', function($c) {
        return new $c['debug.toolbar.extension.runtime.class']($c->get('kernel'));
    });

    $container->set('debug.toolbar.extension.security', function($c) {
        return new $c['debug.toolbar.extension.security.class']($c->get('security'));
    });

    $container->set('debug.toolbar.extension.version', function($c) {
        return new $c['debug.toolbar.extension.version.class']($c->get('kernel'));
    });

    $container->set('debug.toolbar.subscription_manager', function($c) {
        return new $c['debug.toolbar.subscription_manager.class']($c->get('debug.toolbar'));
    });

    $container->set('debug.toolbar', function($c) {
        return new $c['debug.toolbar.class']();
    });


    // Extensions
    $container->extend('debug.toolbar', function($toolbar, $c) {
        $toolbar->addExtension($c->get('debug.toolbar.extension.version'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.controller'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.runtime'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.memory_usage'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.doctrine_query'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.caching'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.security'));

        return $toolbar;
    });

    $container->extend('event_dispatcher', function($dispatcher, $c) {
        // Register all extension events before toolbar injection
        $subscriptionManager = $c->get('debug.toolbar.subscription_manager');
        $subscriptionManager->registerSubscriptions($dispatcher);

        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_injection'), 'onResponse']);

        return $dispatcher;
    });
};
