<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Support\Logger\AppLogger;
use Dotenv\Dotenv;
use Psr\Log\LoggerInterface;

$envFileDir = __DIR__ . '/../';
$envFileName = '.env.testing';

if (getenv('APP_ENV') !== 'testing' or !file_exists($envFileDir . $envFileName)) {
    echo ("this test must be run in environment 'testing'.");
    exit(1);
}

$dotenv = Dotenv::createImmutable($envFileDir, $envFileName);
$dotenv->load();

$container = require __DIR__ . "/../config/dependencies.php";

AppLogger::initialize($container->get(LoggerInterface::class));
