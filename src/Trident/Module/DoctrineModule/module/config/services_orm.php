<?php

return function($container) {
    // Parameters
    $container['doctrine.orm.entity_manager.class'] = 'Doctrine\\ORM\\EntityManager';
    $container['doctrine.orm.tools_setup.class']    = 'Doctrine\\ORM\\Tools\\Setup';

    // Services
    $container->set('doctrine.orm.entity_manager', function($c) {
        $appConfig = $c->get('configuration');
        $ormConfig = $c['doctrine.orm.tools_setup.class']::createAnnotationMetadataConfiguration([], false);

        return $c['doctrine.orm.entity_manager.class']::create([
            'driver'   => 'pdo_mysql',
            'user'     => $appConfig->get('database.default.username'),
            'password' => $appConfig->get('database.default.password'),
            'dbname'   => $appConfig->get('database.default.database'),
        ], $ormConfig);
    });
};
