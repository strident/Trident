<?php

return function ($container) {
    // Parameters
    $container['forms.csrf.session_csrf_provider.class']          = 'Symfony\\Component\\Form\\Extension\\Csrf\\CsrfProvider\\SessionCsrfProvider';
    $container['forms.extension.csrf_extension.class']            = 'Symfony\\Component\\Form\Extension\\Csrf\\CsrfExtension';
    $container['forms.extension.http_foundation_extension.class'] = 'Symfony\\Component\\Form\\Extension\\HttpFoundation\\HttpFoundationExtension';
    $container['forms.extension.validator_extension.class']       = 'Symfony\\Component\\Form\\Extension\\Validator\\ValidatorExtension';
    $container['forms.class']                                     = 'Symfony\\Component\\Form\\Forms';


    // Services
    $container->set('forms.csrf.session_csrf_provider', function($c) {
        return new $c['forms.csrf.session_csrf_provider.class'](
            $c->get('session'),
            $c->get('configuration')->get('security.forms.csrf_secret')
        );
    });

    $container->set('forms.extension.csrf_extension', function($c) {
        return new $c['forms.extension.csrf_extension.class']($c->get('forms.csrf.session_csrf_provider'));
    });

    $container->set('forms.extension.http_foundation_extension', function($c) {
        return new $c['forms.extension.http_foundation_extension.class']();
    });

    $container->set('forms.extension.validator_extension', function($c) {
        return new $c['forms.extension.validator_extension.class']($c->get('validator'));
    });

    $container->set('forms.builder', function($c) {
        return $c['forms.class']::createFormFactoryBuilder()
            ->addExtension($c->get('forms.extension.http_foundation_extension'))
            ->addExtension($c->get('forms.extension.csrf_extension'))
            ->addExtension($c->get('forms.extension.validator_extension'));
    });

    $container->set('form.factory', function($c) {
        return $c->get('forms.builder')->getFormFactory();
    });
};
