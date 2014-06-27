<?php

return function($container) {
    // Paremeters
    $container['security.aegis.authenticator.delegating.class'] = 'Aegis\\Authentication\\Authenticator\\DelegatingAuthenticator';
    $container['security.aegis.storage.session.class']          = 'Trident\\Bridge\\Strident\\Aegis\\Storage\\SessionStorage';
    $container['security.listener.request.class']               = 'Trident\\Module\\SecurityModule\\Listener\\RequestListener';
    $container['security.class']                                = 'Aegis\\Aegis';


    // Services
    $container->set('security.aegis.authenticator.delegating', function($c) {
        return new $c['security.aegis.authenticator.delegating.class']();
    });

    $container->set('security.aegis.storage.session', function($c) {
        $sessionKey = $c->get('configuration')->get('security.session.key', 'trident.session');

        return new $c['security.aegis.storage.session.class']($c->get('session'), $sessionKey);
    });

    $container->set('security.listener.request', function($c) {
        return new $c['security.listener.request.class']();
    });

    $container->set('security', function($c) {
        $security = new $c['security.class']();
        $security->setAuthenticator($c->get('security.aegis.authenticator.delegating'));
        $security->setStorage($c->get('security.aegis.storage.session'));

        return $security;
    });
};
