<?php

return function($container) {
    // Extensions
    $container->extend('doctrine.orm.configuration', function($ormConfig, $c) {
        $appConfig = $c->get('configuration');

        if ( ! $c['kernel.debug'] && 'memcached' === $appConfig->get('caching.default')) {
            $ormConfig->setMetadataCacheImpl($c->get('doctrine.cache.memcached'));
            $ormConfig->setQueryCacheImpl($c->get('doctrine.cache.memcached'));
        }

        return $ormConfig;
    });

    $container->extend('doctrine.orm.entity_manager', function($em, $c) {
        $em->getConfiguration()->setSQLLogger($c->get('doctrine.debug.sql_logger'));

        return $em;
    });
};
