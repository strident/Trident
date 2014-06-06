<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Parameters
    $container['debug.listener.exception.class'] = 'Trident\\Module\\DebugModule\\Listener\\ExceptionListener';
    $container['debug.listener.response.class']  = 'Trident\\Module\\DebugModule\\Listener\\ResponseListener';


    // Services
    $container->set('debug.listener.exception', function($c) {
        return new $c['debug.listener.exception.class']();
    });

    $container->set('debug.listener.response', function($c) {
        return new $c['debug.listener.response.class']();
    });


    // Extensions
    $container->extend('event_dispatcher', function($dispatcher, $c) {
        $dispatcher->addListener(KernelEvents::EXCEPTION, [$c->get('debug.listener.exception'), 'onException']);
        $dispatcher->addListener(KernelEvents::RESPONSE, [$c->get('debug.listener.response'), 'onResponse']);

        return $dispatcher;
    });
};
