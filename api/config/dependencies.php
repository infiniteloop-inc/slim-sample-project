<?php

declare(strict_types=1);

use App\Support\Config\Config;
use App\Support\Redis\RedisManager;
use DI\ContainerBuilder;
use Illuminate\Cache\RedisStore;
use Illuminate\Support\Arr;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\RedisSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

$initConfig = function (): Config {
    return new Config([
        'logger'  => require __DIR__ . '/logger.php',
        'redis'   => require __DIR__ . '/redis.php',
        'session' => require __DIR__ . '/session.php',
    ]);
};

$initLogger = function (ContainerInterface $c): LoggerInterface {
    /* @var Config */
    $config = $c->get('app.config');
    $logger = new Logger($config->getString('logger.name'));
    $processor = new UidProcessor();
    $logger->pushProcessor($processor);
    $handler = new StreamHandler($config->getString('logger.path'), $config->getString('logger.level'));
    $logger->pushHandler($handler);
    return $logger;
};

$initRedis = function (ContainerInterface $c): RedisStore {
    $conf = $c->get('app.config')->getArray('redis');
    $connection = Arr::get($conf, 'connection', 'default');
    $redis = new RedisManager(Arr::pull($conf, 'client', 'predis'), $conf);
    return new RedisStore($redis, $conf['prefix'] ?? '', $connection);
};


$initSession = function (ContainerInterface $c): SessionInterface {
    /** @var Config */
    $config = $c->get('app.config');

    /** @var RedisManager */
    $redisStore = $c->get(RedisStore::class);
    $connection = $config->getString('session.connection');
    $redis = $redisStore->connection($connection)->client();

    $storage = new NativeSessionStorage($config->getArray('session'), new RedisSessionHandler($redis), new MetadataBag('app_meta'));
    return new Session($storage, new AttributeBag('app_attributes'), new FlashBag('app_flashes'));
};

// build container
$builder = new ContainerBuilder();
$builder->addDefinitions([
    Config::class => $initConfig,
    LoggerInterface::class => $initLogger,
    RedisStore::class => $initRedis,
    SessionInterface::class => $initSession,
    'app.config' => DI\get(Config::class),
]);
return $builder->build();
