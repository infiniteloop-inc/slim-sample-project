<?php

declare(strict_types=1);

use App\Support\Config\Config;
use App\Support\Database\TransactionInterface;
use App\Support\Doctrine\ChronosDateTimeType;
use App\Support\Doctrine\Transaction;
use App\Support\Redis\RedisManager;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Illuminate\Cache\RedisStore;
use Illuminate\Support\Arr;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\RedisSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

$initConfig = function (): Config {
    return new Config([
        'db'      => require __DIR__ . '/database.php',
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

$initEntityManager = function (ContainerInterface $c): EntityManagerInterface {
    /** @var Config */
    $config = $c->get(Config::class);

    Type::overrideType('datetime', ChronosDateTimeType::class);

    if (env('APP_ENV') === 'local') {
        $queryCache = new ArrayAdapter();
        $metadataCache = new ArrayAdapter();
    } else {
        $queryCache = new PhpFilesAdapter('doctrine_queries');
        $metadataCache = new PhpFilesAdapter('doctrine_metadata');
    }

    $doctrineConfig = new Configuration();
    $doctrineConfig->setMetadataCache($metadataCache);
    $driverImpl = new AttributeDriver($config->getArray('db.metadata_dirs'));
    $doctrineConfig->setMetadataDriverImpl($driverImpl);
    $doctrineConfig->setQueryCache($queryCache);
    $doctrineConfig->setProxyDir($config->getString('db.proxy_dir'));
    $doctrineConfig->setProxyNamespace($config->getString('db.proxy_namespace'));

    $doctrineConfig->setNamingStrategy(new UnderscoreNamingStrategy(numberAware: true));

    if (env('APP_ENV') === 'local') {
        $doctrineConfig->setAutoGenerateProxyClasses(true);
    } else {
        $doctrineConfig->setAutoGenerateProxyClasses(false);
    }

    /** @var LoggerInterface */
    $logger = $c->get(LoggerInterface::class);
    $doctrineConfig->setMiddlewares([new Middleware($logger)]);

    $connection = DriverManager::getConnection($config->getArray('db.user'), $doctrineConfig);

    return new EntityManager($connection, $doctrineConfig);
};

$initTransaction = function (ContainerInterface $c): TransactionInterface {
    /** @var EntityManagerInterface */
    $em = $c->get(EntityManagerInterface::class);

    return new Transaction($em);
};

// build container
$builder = new ContainerBuilder();
$builder->addDefinitions([
    Config::class => $initConfig,
    LoggerInterface::class => $initLogger,
    RedisStore::class => $initRedis,
    SessionInterface::class => $initSession,
    EntityManagerInterface::class => $initEntityManager,
    TransactionInterface::class => $initTransaction,
    'app.config' => DI\get(Config::class),
]);
return $builder->build();
