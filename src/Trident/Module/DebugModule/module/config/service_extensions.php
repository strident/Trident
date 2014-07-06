<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Extensions
    $container->extend('event_dispatcher', function($dispatcher, $c) {
        // Register all extension events before toolbar injection
        $subscriptionManager = $c->get('debug.toolbar.subscription_manager');
        $subscriptionManager->registerSubscriptions($dispatcher);

        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_injection'), 'onResponse']);

        return $dispatcher;
    });
};
