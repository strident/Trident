<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Parameters
    $container['debug.listener.exception.class']             = 'Trident\\Module\\DebugModule\\Listener\\ExceptionListener';
    $container['debug.listener.toolbar_response.class']      = 'Trident\\Module\\DebugModule\\Listener\\ToolbarResponseListener';
    $container['debug.toolbar.extension.memory_usage.class'] = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentMemoryUsageExtension';
    $container['debug.toolbar.extension.runtime.class']      = 'Trident\\Module\\DebugModule\\Toolbar\\Extension\\TridentRuntimeExtension';
    $container['debug.toolbar.class']                        = 'Trident\\Component\\Debug\\Toolbar\\Toolbar';


    // Services
    $container->set('debug.listener.exception', function($c) {
        return new $c['debug.listener.exception.class']();
    });

    $container->set('debug.listener.toolbar_response', function($c) {
        return new $c['debug.listener.toolbar_response.class']($c->get('debug.toolbar'));
    });

    $container->set('debug.toolbar.extension.memory_usage', function($c) {
        return new $c['debug.toolbar.extension.memory_usage.class']();
    });

    $container->set('debug.toolbar.extension.runtime', function($c) {
        return new $c['debug.toolbar.extension.runtime.class']($c->get('kernel'));
    });

    $container->set('debug.toolbar', function($c) {
        return new $c['debug.toolbar.class']();
    });


    // Extensions
    $container->extend('debug.toolbar', function($toolbar, $c) {
        $toolbar->addExtension($c->get('debug.toolbar.extension.memory_usage'));
        $toolbar->addExtension($c->get('debug.toolbar.extension.runtime'));

        return $toolbar;
    });

    $container->extend('event_dispatcher', function($dispatcher, $c) {
        // $dispatcher->addListener(KernelEvents::EXCEPTION, [$c->get('debug.listener.exception'), 'onException']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.toolbar_response'), 'onResponse']);

        return $dispatcher;
    });
};
