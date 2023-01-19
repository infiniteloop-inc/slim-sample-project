<?php

declare(strict_types=1);

if (!function_exists('env')) {
    function env(string $name, mixed $default = null): mixed
    {
        if (!array_key_exists($name, $_ENV)) {
            return $default;
        }
        /** @psalm-suppress MixedAssignment */
        $value = $_ENV[$name];
        if (is_string($value)) {
            switch (strtolower($value)) {
                case 'true':
                    return true;
                case 'false':
                    return false;
                case 'null':
                    return null;
                default:
                    return $value;
            }
        }
        return $value;
    }
}
