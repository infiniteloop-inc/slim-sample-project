<?php

// phpcs:ignoreFile

declare(strict_types=1);

use Dotenv\Dotenv;

(function () {
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../", ".env");
    $dotenv->load();
})();

if (!function_exists("c")) {
    function c(string $key): mixed
    {
        static $container = null;
        if (is_null($container)) {
            $container = require __DIR__ . "/../config/dependencies.php";
        }
        return $container->get($key);
    }
}
