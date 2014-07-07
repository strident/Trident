<?php

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
};
