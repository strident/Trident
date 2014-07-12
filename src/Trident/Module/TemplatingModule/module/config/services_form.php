<?php

use Symfony\Bridge\Twig\Form\TwigRenderer;

return function($container) {
    // Parameters
    $container['templating.engine.twig.forms.form_extension.class'] = 'Symfony\\Bridge\\Twig\\Extension\\FormExtension';
    $container['templating.engine.twig.forms.form_engine.class']    = 'Symfony\\Bridge\\Twig\\Form\\TwigRendererEngine';


    // Services
    $container->set('templating.engine.twig.forms.form_extension', function($c) {
        $formEngine = new $c['templating.engine.twig.forms.form_engine.class']([
            $c->get('configuration')->get('form.theme', 'TridentTemplatingModule:Form:form_div_layout.html.twig')
        ]);

        return new $c['templating.engine.twig.forms.form_extension.class'](
            new TwigRenderer(
                $formEngine,
                $c->get('forms.csrf.session_csrf_provider')
            )
        );
    });
};
