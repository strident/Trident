<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Extensions
    $container->extend('debug.toolbar', function($toolbar, $c) {
        $toolbar->addExtension($c->get('debug.toolbar.extension.version'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.controller'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.runtime'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.memory_usage'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.caching'));

        return $toolbar;
    }, false);

    $container->extend('event_dispatcher', function($dispatcher, $c) {
        // Register all extension events before toolbar injection
        $subscriptionManager = $c->get('debug.toolbar.subscription_manager');
        $subscriptionManager->registerSubscriptions($dispatcher);

        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_injection'), 'onResponse']);

        return $dispatcher;
    });
};
