<?php

declare(strict_types=1);

// Doctrine Setup

use Cache\Adapter\Apcu\ApcuCachePool;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;

use function Firehed\Container\env;

return [
    'database_url' => env('DATABASE_URL'),

    'localPsr6Cache' => function ($c) {
        if ($c->get('isDevMode')) {
            return new ArrayCachePool();
        } else {
            return new ApcuCachePool();
        }
    },
    // Attribute driver docs:
    // https://www.doctrine-project.org/projects/doctrine-orm/en/2.10/reference/attributes-reference.html
    MappingDriver::class => fn() => new AttributeDriver(['src']),

    EntityManagerInterface::class => function ($c) {
        $isDevMode = $c->get('isDevMode');

        $proxyDir = '.generated/doctrine-proxies';

        $cache = DoctrineProvider::wrap($c->get('localPsr6Cache'));

        $config = Setup::createConfiguration(
            isDevMode: $isDevMode,
            proxyDir: $proxyDir,
            cache: $cache,
        );

        $config->setMetadataDriverImpl($c->get(MappingDriver::class));

        // https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html
        $connection = [
            'driver' => 'pdo_mysql',
            // This is set automatically from above, but provides a useful starting
            // reference point for debugging
            'driverClass' => \Doctrine\DBAL\Driver\PDO\MySQL\Driver::class,
            'driverOptions' => [],

            'url' => $c->get('database_url'),
            // In the future for primary/replica support, set the following:
            // 'wrapperClass' => \Doctrine\DBAL\Connections\PrimaryReadReplicaConnection::class,
            // 'primary' => [
            //     'url' => '...',
            // ],
            // 'replica' => [
            //     [
            //         'url' => '...',
            //     ],
            // ],

        ];

        return EntityManager::create($connection, $config);
    },
];
