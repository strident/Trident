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

        $paths = [];
        foreach ($c->get('kernel')->getModules() as $module) {
            $path = $module->getRootDir().'/Data/Entity';

            if (file_exists($path) && is_dir($path)) {
                $paths[] = $path;
            }
        }

        return new $c['doctrine.annotations.driver.class']($reader, $paths);
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
            'driver'   => $appConfig->get('database.default.driver', 'pdo_mysql'),
            'host'     => $appConfig->get('database.default.host'),
            'user'     => $appConfig->get('database.default.username'),
            'password' => $appConfig->get('database.default.password'),
            'dbname'   => $appConfig->get('database.default.database'),
        ], $c->get('doctrine.orm.configuration'));
    });
};
