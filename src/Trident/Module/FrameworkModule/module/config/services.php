<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;

return function($container) {
    // Parameters
    $container['controller_resolver.class'] = 'Trident\\Component\\HttpKernel\\Controller\\ControllerResolver';
    $container['event_dispatcher.class']    = 'Symfony\\Component\\EventDispatcher\\EventDispatcher';
    $container['request_context.class']     = 'Symfony\\Component\\Routing\\RequestContext';
    $container['router.class']              = 'Symfony\\Component\\Routing\\Router';
    $container['route_collection.class']    = 'Symfony\\Component\\Routing\\RouteCollection';
    $container['url_matcher.class']         = 'Symfony\\Component\\Routing\\Matcher\\UrlMatcher';
    $container['session.class']             = 'Symfony\\Component\\HttpFoundation\\Session\\Session';


    // Services
    $container->set('controller_resolver', function($c) {
        return new $c['controller_resolver.class']($c);
    });

    $container->set('event_dispatcher', function($c) {
        return new $c['event_dispatcher.class']();
    });

    $container->set('request_context', function($c) {
        $context = new $c['request_context.class']();
        $context->fromRequest($c->get('request'));

        return new $c['request_context.class']();
    });

    $container->set('router', function($c) {
        $kernel  = $c->get('kernel');
        $locator = new FileLocator([
            $kernel->getRootDir().'/config'
        ]);

        $options = ['cache_dir' => $kernel->getRootDir().'/cache/router'];

        if ($kernel->isDebugMode()) {
            $options = ['debug' => true];
        }

        return new $c['router.class'](
            new YamlFileLoader($locator),
            'routes.yml',
            $options,
            $c->get('request_context')
        );
    });

    $container->set('route_collection', function($c) {
        return new $c['route_collection.class']();
    });

    $container->set('url_matcher', function($c) {
        $routes  = $c->get('route_collection');
        $context = $c->get('request_context');

        return new $c['url_matcher.class']($routes, $context);
    });
};
