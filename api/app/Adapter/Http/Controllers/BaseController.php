<?php

declare(strict_types=1);

namespace App\Adapter\Http\Controllers;

use App\Domain\User\User;
use App\Exceptions\RuntimeException;

class BaseController
{
    /** @var User|null $user */
    private static ?User $user = null;

    public static function setLoggedInUser(User $user): void
    {
        self::$user = $user;
    }

    protected function isLoggedIn(): bool
    {
        return self::$user !== null;
    }

    protected function getUser(): ?User
    {
        return self::$user;
    }

    protected function getLoggedInUser(): User
    {
        if (self::$user === null) {
            throw new RuntimeException('No logged-in user set.');
        }
        return self::$user;
    }
}
