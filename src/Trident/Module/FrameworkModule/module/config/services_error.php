<?php

return function($container) {
    // Parameters
    $container['error.listener.exception.class'] = 'Trident\\Module\\FrameworkModule\\Listener\\ExceptionListener';
    $container['error.controller.class']        = 'Trident\\Module\\FrameworkModule\\Controller\\ExceptionController::exceptionAction';


    // Services
    $container->set('error.listener.exception', function($c) {
        return new $c['error.listener.exception.class']($c['error.controller.class']);
    });
};
