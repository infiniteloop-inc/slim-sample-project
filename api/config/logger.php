<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'app'),
    'path' => __DIR__ . '/../storage/logs/' . (string)env('APP_ENV', 'production') . '-' . PHP_SAPI . '.log',
    'level' => env('LOG_LEVEL', 'info'),
];
