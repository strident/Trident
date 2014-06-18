<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Parameters
    $container['error.listener.exception.class'] = 'Trident\\Module\\FrameworkModule\\Listener\\ExceptionListener';
    $container['error.controller.class']        = 'Trident\\Module\\FrameworkModule\\Controller\\ExceptionController::exceptionAction';


    // Services
    $container->set('error.listener.exception', function($c) {
        return new $c['error.listener.exception.class']($c['error.controller.class']);
    });


    // Extensions
    $container->extend('event_dispatcher', function($dispatcher, $c) {
        $dispatcher->addListener(KernelEvents::EXCEPTION, [$c->get('error.listener.exception'), 'onException']);

        return $dispatcher;
    });
};
