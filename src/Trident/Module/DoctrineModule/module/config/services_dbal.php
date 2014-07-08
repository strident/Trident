<?php

return function($container) {
    // Parameters
    $container['doctrine.dbal.configuration.class']  = 'Doctrine\\DBAL\\Configuration';
    $container['doctrine.dbal.driver_manager.class'] = 'Doctrine\\DBAL\\DriverManager';

    // Services
    $container->set('doctrine.dbal.configuration', function($c) {
        return new $c['doctrine.dbal.configuration.class']();
    });

    $container->set('doctrine.dbal.connection', function($c) {
        $configuration = $c->get('configuration');

        return $c['doctrine.dbal.driver_manager.class']::getConnection([
            'host'     => $configuration->get('database.default.host', 'localhost'),
            'user'     => $configuration->get('database.default.username', 'root'),
            'password' => $configuration->get('database.default.password', ''),
            'dbname'   => $configuration->get('database.default.database', ''),
            'driver'   => $configuration->get('database.default.driver', 'pdo_mysql')
        ], $c->get('doctrine.dbal.configuration'));
    });
};
