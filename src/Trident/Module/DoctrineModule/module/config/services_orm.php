<?php

return function($container) {
    // Parameters
    $container['doctrine.orm.entity_manager.class'] = 'Doctrine\\ORM\\EntityManager';
    $container['doctrine.orm.tools_setup.class']    = 'Doctrine\\ORM\\Tools\\Setup';
    $container['doctrine.annotations.driver.class'] = 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver';
    $container['doctrine.annotations.reader.class'] = 'Doctrine\\Common\\Annotations\\AnnotationReader';


    // Services
    $container->set('doctrine.annotations.driver', function($c) {
        $reader = $c->get('doctrine.annotations.reader');

        return new $c['doctrine.annotations.driver.class']($reader, []);
    });

    $container->set('doctrine.annotations.reader', function($c) {
        return new $c['doctrine.annotations.reader.class']();
    });

    $container->set('doctrine.orm.entity_manager', function($c) {
        $appConfig = $c->get('configuration');
        $ormConfig = $c['doctrine.orm.tools_setup.class']::createAnnotationMetadataConfiguration([], false);
        $ormConfig->setMetadataDriverImpl($c->get('doctrine.annotations.driver'));

        // This needs to be abstracted, and configurable
        // $memcached = new \Memcached();
        // $memcached->addServer('localhost', 11211);

        // Ideally make the following into a factory service?
        // $cache = new \Doctrine\Common\Cache\MemcachedCache();
        // $cache->setMemcached($memcached);

        // $ormConfig->setMetadataCacheImpl($cache);
        // $ormConfig->setQueryCacheImpl($cache);
        // ---


        return $c['doctrine.orm.entity_manager.class']::create([
            'driver'   => 'pdo_mysql',
            'user'     => $appConfig->get('database.default.username'),
            'password' => $appConfig->get('database.default.password'),
            'dbname'   => $appConfig->get('database.default.database'),
        ], $ormConfig);
    });
};
