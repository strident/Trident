<?php

return function($container) {
    // Parameters
    $container['%controller_resolver.class%'] = 'Strident\\Component\\HttpKernel\\Controller\\ControllerResolver';
    $container['%event_dispatcher.class%']    = 'Symfony\\Component\\EventDispatcher\\EventDispatcher';
    $container['%http_kernel.class%']         = 'Symfony\\Component\\HttpKernel\\HttpKernel';
    $container['%request_stack.class%']       = 'Symfony\\Component\\HttpFoundation\\RequestStack';
    $container['%route_collection.class%']    = 'Symfony\\Component\\Routing\\RouteCollection';
    $container['%listener.exception.class%']  = 'Symfony\\Component\\HttpKernel\\EventListener\\ExceptionListener';
    $container['%listener.response.class%']   = 'Symfony\\Component\\HttpKernel\\EventListener\\ResponseListener';
    $container['%listener.router.class%']     = 'Symfony\\Component\\HttpKernel\\EventListener\\RouterListener';
    $container['%exception_controller%']      = 'Strident\\Module\\FrameworkModule\\Controller\\ExceptionController::exceptionAction';


    // Services
    $container->set('controller_resolver', function($c) {
        return new $c['%controller_resolver.class%']($c);
    });

    $container->set('event_dispatcher', function($c) {
        return new $c['%event_dispatcher.class%']();
    });

    $container->set('http_kernel', function($c) {
        $dispatcher   = $c['event_dispatcher'];
        $resolver     = $c['controller_resolver'];
        $requestStack = $c['request_stack'];

        return new $c['%http_kernel.class%']($dispatcher, $resolver, $requestStack);
    });

    $container->set('listener.exception', function($c) {
        return new $c['%listener.exception.class%']($c['%exception_controller%']);
    });

    $container->set('listener.response', function($c) {
        return new $c['%listener.response.class%']('UTF-8');
    });

    $container->set('listener.router', function($c) {
        $routes  = $c['route_collection'];
        $context = new Symfony\Component\Routing\RequestContext();
        $matcher = new Symfony\Component\Routing\Matcher\UrlMatcher($routes, $context);

        return new $c['%listener.router.class%']($matcher);
    });

    $container->set('request_stack', function($c) {
        return new $c['%request_stack.class%'];
    });

    $container->set('route_collection', function($c) {
        return new $c['%route_collection.class%']();
    });


    // Extensions
    $container->extend('event_dispatcher', function($dispatcher, $c) {
        $dispatcher->addSubscriber($c['listener.exception']);
        $dispatcher->addSubscriber($c['listener.response']);
        $dispatcher->addSubscriber($c['listener.router']);

        return $dispatcher;
    });
};
