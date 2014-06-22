<?php

return function($container) {
    // Paremeters
    $container['security.aegis.provider.delegating.class'] = 'Aegis\\Authentication\\Provider\\DelegatingAuthenticationProvider';
    $container['security.aegis.storage.session.class']     = 'Trident\\Bridge\\Strident\\Aegis\\Storage\\SessionStorage';
    $container['security.class']                           = 'Aegis\\Aegis';


    // Services
    $container->set('security.aegis.provider.delegating', function($c) {
        return new $c['security.aegis.provider.delegating.class']();
    });

    $container->set('security.aegis.storage.session', function($c) {
        $sessionKey = $c->get('configuration')->get('security.session.key', 'trident.session');

        return new $c['security.aegis.storage.session.class']($c->get('session'), $sessionKey);
    });

    $container->set('security', function($c) {
        $security = new $c['security.class']();
        $security->setProvider($c->get('security.aegis.provider.delegating'));
        $security->setStorage($c->get('security.aegis.storage.session'));
        $security->initialize();

        return $security;
    });


    // Extensions
    $container->extend('security.aegis.provider.delegating', function($provider, $c) {
        $provider->addProvider(new \Aegis\Authentication\Provider\FakeUserProvider($c->get('request')));

        return $provider;
    });
};
