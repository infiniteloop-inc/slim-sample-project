<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$envFileDir = __DIR__ . '/../';
$envFileName = '.env.testing';

if (getenv('APP_ENV') !== 'testing' or !file_exists($envFileDir . $envFileName)) {
    echo ("this test must be run in environment 'testing'.");
    exit(1);
}

$dotenv = Dotenv::createImmutable($envFileDir, $envFileName);
$dotenv->load();
