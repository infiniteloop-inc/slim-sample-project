<?php

declare(strict_types=1);

use App\Support\Config\Config;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

$initConfig = function (): Config {
    return new Config([
        'logger'  => require __DIR__ . '/logger.php',
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

// build container
$builder = new ContainerBuilder();
$builder->addDefinitions([
    Config::class => $initConfig,
    LoggerInterface::class => $initLogger,
    'app.config' => DI\get(Config::class),
]);
return $builder->build();
