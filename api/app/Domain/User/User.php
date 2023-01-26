<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Exceptions\InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class User
{
    protected const MIN_NAME_LENGTH = 2;
    protected const MAX_NAME_LENGTH = 20;

    public function __construct(
        public UserDto $userDto
    ) {
    }

    public function regenerateAuthToken(): void
    {
        $this->userDto->authToken = self::generateAuthToken();
    }

    /**
     * @psalm-internal App\Domain\User
     */
    public static function generateAuthToken(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @psalm-internal App\Domain\User
     */
    public static function validateName(string $name): void
    {
        $length = strlen($name);
        if ($length < self::MIN_NAME_LENGTH) {
            throw InvalidArgumentException::create('User name must be ' . self::MIN_NAME_LENGTH . ' characters or more.')->withContext(['name' => $name]);
        }
        if (self::MAX_NAME_LENGTH < $length) {
            throw InvalidArgumentException::create('User name must be ' . self::MAX_NAME_LENGTH . ' characters or less.')->withContext(['name' => $name]);
        }
    }
}
