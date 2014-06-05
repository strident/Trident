<?php

return function($container) {
    // Parameters
    $container['caching.class']                  = 'Trident\\Component\\Caching\\Caching';
    $container['caching.driver.memcached.class'] = 'Trident\\Component\\Caching\\Driver\\MemcachedCacheDriver';
    $container['caching.driver.null.class']      = 'Trident\\Component\\Caching\\Driver\\NullCacheDriver';
    $container['caching.factory.class']          = 'Trident\\Component\\Caching\\CacheDriverFactory';
    $container['caching.proxy']                  = 'Trident\\Component\\Caching\\CachingProxy';


    // Services
    $container->set('caching', function($c) {
        return $c->get('caching.factory')->build();
    });

    $container->set('caching.driver.memcached', function($c) {
        $configuration = $c->get('configuration');

        $driver = new $c['caching.driver.memcached.class']();
        $driver->setMemcached($c->get('caching.raw.memcached'));

        return $driver;
    });

    $container->set('caching.driver.null', function($c) {
        return new $c['caching.driver.null.class']();
    });

    $container->set('caching.factory', $container->factory(function($c) {
        $factory = new $c['caching.factory.class']($c, $c->get('configuration'), $c['kernel.debug']);
        $factory->addDriver('null', 'caching.driver.null');
        $factory->addDriver('memcached', 'caching.driver.memcached');
        $factory->setDebugDriver('null');

        return $factory;
    }));

    $container->set('caching.proxy', function($c) {
        $proxy = new $c['caching.proxy']();
        $proxy->setDriver($c->get('caching'));

        return $proxy;
    });

    $container->set('caching.raw.memcached', function($c) {
        $configuration = $c->get('configuration');

        $memcached = new \Memcached();
        $memcached->addServer(
            $configuration->get('caching.memcached.host', 'localhost'),
            $configuration->get('caching.memcached.port', 11211)
        );

        return $memcached;
    });
};
