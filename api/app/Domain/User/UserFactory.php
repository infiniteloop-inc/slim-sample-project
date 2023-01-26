<?php

declare(strict_types=1);

namespace App\Domain\User;

use Ramsey\Uuid\Uuid;

class UserFactory
{
    public function create(string $name): User
    {
        User::validateName($name);

        return new User(
            UserDto::fromArray([
                'userId'    => self::generateId(),
                'name'      => $name,
                'authToken' => User::generateAuthToken(),
            ])
        );
    }

    protected static function generateId(): string
    {
        return Uuid::uuid4()->toString();
    }
}
