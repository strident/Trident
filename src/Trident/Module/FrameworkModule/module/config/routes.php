<?php

use Symfony\Component\Routing\Route;

return function($routes) {
    // Routes
    $routes->add('trident_welcome', new Route('/_trident', [
        '_controller' => 'Trident\\Module\\FrameworkModule\\Controller\\WelcomeController::indexAction'
    ]));
};
