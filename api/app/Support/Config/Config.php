<?php

declare(strict_types=1);

namespace App\Support\Config;

use Adbar\Dot;

final class Config
{
    /** @var Dot */
    private $config;

    public function __construct(array $config)
    {
        $this->config = new Dot($config);
    }

    public function get(string $key): mixed
    {
        return $this->config->get($key);
    }

    public function getString(string $key): string
    {
        $value = $this->config->get($key);
        assert(is_string($value));
        return $value;
    }

    public function getBool(string $key): bool
    {
        $value = $this->config->get($key);
        assert(is_bool($value));
        return $value;
    }

    public function getInt(string $key): int
    {
        $value = $this->config->get($key);
        assert(is_int($value));
        return $value;
    }

    public function getFloat(string $key): float
    {
        $value = $this->config->get($key);
        assert(is_float($value));
        return $value;
    }

    public function getArray(string $key): array
    {
        $value = $this->config->get($key);
        assert(is_array($value));
        return $value;
    }
}
