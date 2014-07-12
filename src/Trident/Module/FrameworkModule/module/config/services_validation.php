<?php

return function($container) {
    // Parameters
    $container['validation.class'] = 'Symfony\\Component\\Validator\\Validation';


    // Services
    $container->set('validator', function($c) {
        return $c['validation.class']::createValidator();
    });
};
