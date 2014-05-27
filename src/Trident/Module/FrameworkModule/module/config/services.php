<?php

return function($container) {
    // Parameters
    $container['controller_resolver.class'] = 'Trident\\Component\\HttpKernel\\Controller\\ControllerResolver';
    $container['event_dispatcher.class']    = 'Symfony\\Component\\EventDispatcher\\EventDispatcher';
    $container['exception_controller']      = 'Trident\\Module\\FrameworkModule\\Controller\\ExceptionController::exceptionAction';
    $container['http_kernel.class']         = 'Symfony\\Component\\HttpKernel\\HttpKernel';
    $container['listener.exception.class']  = 'Symfony\\Component\\HttpKernel\\EventListener\\ExceptionListener';
    $container['listener.response.class']   = 'Symfony\\Component\\HttpKernel\\EventListener\\ResponseListener';
    $container['listener.router.class']     = 'Symfony\\Component\\HttpKernel\\EventListener\\RouterListener';
    $container['request_stack.class']       = 'Symfony\\Component\\HttpFoundation\\RequestStack';
    $container['route_collection.class']    = 'Symfony\\Component\\Routing\\RouteCollection';


    // Services
    $container->set('controller_resolver', function($c) {
        return new $c['controller_resolver.class']($c);
    });

    $container->set('event_dispatcher', function($c) {
        return new $c['event_dispatcher.class']();
    });

    $container->set('http_kernel', function($c) {
        $dispatcher   = $c->get('event_dispatcher');
        $resolver     = $c->get('controller_resolver');
        $requestStack = $c->get('request_stack');

        return new $c['http_kernel.class']($dispatcher, $resolver, $requestStack);
    });

    $container->set('listener.exception', function($c) {
        return new $c['listener.exception.class']($c['exception_controller']);
    });

    $container->set('listener.response', function($c) {
        return new $c['listener.response.class']('UTF-8');
    });

    $container->set('listener.router', function($c) {
        $routes  = $c->get('route_collection');
        $context = new Symfony\Component\Routing\RequestContext();
        $matcher = new Symfony\Component\Routing\Matcher\UrlMatcher($routes, $context);

        return new $c['listener.router.class']($matcher);
    });

    $container->set('request_stack', function($c) {
        return new $c['request_stack.class'];
    });

    $container->set('route_collection', function($c) {
        return new $c['route_collection.class']();
    });


    // Extensions
    $container->extend('event_dispatcher', function($dispatcher, $c) {
        $dispatcher->addSubscriber($c->get('listener.exception'));
        $dispatcher->addSubscriber($c->get('listener.response'));
        $dispatcher->addSubscriber($c->get('listener.router'));

        return $dispatcher;
    });
};