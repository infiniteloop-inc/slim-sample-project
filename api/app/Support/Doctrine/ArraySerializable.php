<?php

declare(strict_types=1);

namespace App\Support\Doctrine;

trait ArraySerializable
{
    public static function fromArray(array $data): self
    {
        $obj = new self();
        foreach ($data as $key => $value) {
            assert(property_exists(self::class, $key), 'Should not contain the key "' . $key . '" what is not defined in ' . static::class);
            $obj->$key = $value;
        }
        return $obj;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
