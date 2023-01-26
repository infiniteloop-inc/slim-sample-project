<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Support\Doctrine\Query;

/**
 * @extends Query<UserDto>
 */
class UserQuery extends Query
{
    /**
     * @return class-string<UserDto>
     */
    public function getEntityClassName(): string
    {
        return UserDto::class;
    }
}
