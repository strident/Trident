<?php

return function($container) {
    // Extensions
    $container->extend('debug.toolbar', function($toolbar, $c) {
        $toolbar->addExtension($c->get('doctrine.debug.toolbar.extension.query'));

        return $toolbar;
    });
};
