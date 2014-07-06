<?php

return function($container) {
    // Parameters
    $container['doctrine.debug.sql_logger.class']              = 'Doctrine\\DBAL\\Logging\\DebugStack';
    $container['doctrine.debug.toolbar.extension.query.class'] = 'Trident\\Module\\DoctrineModule\\Debug\\Toolbar\\Extension\\TridentDoctrineQueryExtension';


    // Services
    $container->set('doctrine.debug.sql_logger', function($c) {
        return new $c['doctrine.debug.sql_logger.class']();
    });

    $container->set('doctrine.debug.toolbar.extension.query', function($c) {
        return new $c['doctrine.debug.toolbar.extension.query.class']($c->get('doctrine.debug.sql_logger'));
    });
};
