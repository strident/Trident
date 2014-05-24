<?php

use Symfony\Component\Routing\Route;

return function($routes) {
    $routes->add('strident_test', new Route('/', array(
        '_controller' => 'Strident\\Module\\FrameworkModule\\Controller\\TestController::testAction'
    )));
};
