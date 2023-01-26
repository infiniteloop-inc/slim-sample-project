<?php

declare(strict_types=1);

return [
    'dev_mode' => env('DEBUG', false),
    'metadata_dirs' => [__DIR__ . '/../app/Domain'],
    'proxy_dir' => './../app/DoctrineProxies',
    'proxy_namespace' => 'App\DoctrineProxy',

    // user DB connection
    'user' => [
        'driver'        => 'pdo_mysql',
        'host'          => env('MYSQL_HOST'),
        'port'          => env('MYSQL_PORT'),
        'dbname'        => env('MYSQL_DATABASE'),
        'user'          => env('MYSQL_USER'),
        'password'      => env('MYSQL_PASSWORD'),
        'charset'       => 'utf8mb4',
        'driverOptions' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_general_ci',
        ],
        'defaultTableOptions' => [
            'charset' => 'utf8mb4',
            'collate' => 'utf8mb4_general_ci'
        ]
    ],
];
