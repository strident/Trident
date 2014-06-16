<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Parameters
    $container['debug.listener.toolbar_controller.class']    = 'Trident\\Module\\DebugModule\\Listener\\ToolbarControllerListener';
    $container['debug.listener.toolbar_injection.class']     = 'Trident\\Module\\DebugModule\\Listener\\ToolbarInjectionResponseListener';
    $container['debug.toolbar.extension.controller.class']   = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentControllerExtension';
    $container['debug.toolbar.extension.memory_usage.class'] = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentMemoryUsageExtension';
    $container['debug.toolbar.extension.runtime.class']      = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentRuntimeExtension';
    $container['debug.toolbar.extension.version.class']      = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentVersionExtension';
    $container['debug.toolbar.class']                        = 'Trident\\Component\\Debug\\Toolbar\\Toolbar';


    // Services
    $container->set('debug.listener.toolbar_controller', function($c) {
        return new $c['debug.listener.toolbar_controller.class']($c->get('debug.toolbar.extension.controller'));
    });

    $container->set('debug.listener.toolbar_injection', function($c) {
        return new $c['debug.listener.toolbar_injection.class']($c->get('debug.toolbar'));
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

    $container->set('debug.toolbar', function($c) {
        return new $c['debug.toolbar.class']();
    });


    // Extensions
    $container->extend('debug.toolbar', function($toolbar, $c) {
        $toolbar->addExtension($c->get('debug.toolbar.extension.version'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.controller'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.runtime'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.memory_usage'));

        return $toolbar;
    });

    $container->extend('event_dispatcher', function($dispatcher, $c) {
        $dispatcher->addListener(KernelEvents::CONTROLLER, [$c->get('debug.listener.toolbar_controller'), 'onController']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_controller'), 'onResponse']);

        // Must be placed after some of the other events
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_injection'), 'onResponse']);

        return $dispatcher;
    });
};
