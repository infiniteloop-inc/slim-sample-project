<?php

declare(strict_types=1);

namespace App\Support\Logger;

use LogicException;
use Psr\Log\LoggerInterface;

final class AppLogger
{
    private static LoggerInterface | null $logger = null;

    public static function initialize(LoggerInterface $logger): void
    {
        if (self::$logger) {
            throw new LogicException('AppLogger is already set.');
        }
        self::$logger = $logger;
    }

    public static function get(): LoggerInterface
    {
        if (is_null(self::$logger)) {
            throw new LogicException('AppLogger is not set.');
        }
        return self::$logger;
    }

    private function __construct()
    {
    }
}
