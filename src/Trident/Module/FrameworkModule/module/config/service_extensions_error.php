<?php

use Trident\Component\HttpKernel\KernelEvents;

return function($container) {
    // Extensions
    $container->extend('event_dispatcher', function($dispatcher, $c) {
        $dispatcher->addListener(KernelEvents::EXCEPTION, [$c->get('error.listener.exception'), 'onException']);

        return $dispatcher;
    });
};
