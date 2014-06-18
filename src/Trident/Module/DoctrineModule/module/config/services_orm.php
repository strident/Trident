<?php

return function($container) {
    // Parameters
    $container['doctrine.orm.entity_manager.class'] = 'Doctrine\\ORM\\EntityManager';
    $container['doctrine.orm.tools_setup.class']    = 'Doctrine\\ORM\\Tools\\Setup';
    $container['doctrine.annotations.driver.class'] = 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver';
    $container['doctrine.annotations.reader.class'] = 'Doctrine\\Common\\Annotations\\AnnotationReader';
    $container['doctrine.orm.sql_logger.class']     = 'Doctrine\\DBAL\\Logging\\DebugStack';


    // Services
    $container->set('doctrine.annotations.driver', function($c) {
        $reader = $c->get('doctrine.annotations.reader');

        return new $c['doctrine.annotations.driver.class']($reader, []);
    });

    $container->set('doctrine.annotations.reader', function($c) {
        return new $c['doctrine.annotations.reader.class']();
    });

    $container->set('doctrine.cache.memcached', function($c) {
        $cache = new \Doctrine\Common\Cache\MemcachedCache();
        $cache->setMemcached($c->get('caching.raw.memcached'));

        return $cache;
    });

    $container->set('doctrine.orm.configuration', function($c) {
        $ormConfig = $c['doctrine.orm.tools_setup.class']::createAnnotationMetadataConfiguration([], false);
        $ormConfig->setMetadataDriverImpl($c->get('doctrine.annotations.driver'));

        return $ormConfig;
    });

    $container->set('doctrine.orm.entity_manager', function($c) {
        $appConfig = $c->get('configuration');

        return $c['doctrine.orm.entity_manager.class']::create([
            'driver'   => 'pdo_mysql',
            'user'     => $appConfig->get('database.default.username'),
            'password' => $appConfig->get('database.default.password'),
            'dbname'   => $appConfig->get('database.default.database'),
        ], $c->get('doctrine.orm.configuration'));
    });

    $container->set('doctrine.orm.sql_logger', function($c) {
        return new $c['doctrine.orm.sql_logger.class']();
    });

    // Extensions
    $container->extend('doctrine.orm.configuration', function($ormConfig, $c) {
        $appConfig = $c->get('configuration');

        if (! $c['kernel.debug'] && 'memcached' === $appConfig->get('caching.default')) {
            $ormConfig->setMetadataCacheImpl($c->get('doctrine.cache.memcached'));
            $ormConfig->setQueryCacheImpl($c->get('doctrine.cache.memcached'));
        }

        return $ormConfig;
    });

    $container->extend('doctrine.orm.entity_manager', function($em, $c) {
        $em->getConfiguration()->setSQLLogger($c->get('doctrine.orm.sql_logger'));

        return $em;
    });
};
