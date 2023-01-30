<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\User\UserQuery;
use App\Exceptions\RuntimeException;

class GetUser
{
    public function __construct(
        private UserQuery $userQuery,
    ) {
    }

    public function execute(string $userId): array
    {
        $userDto = $this->userQuery->findOneBy(['userId' => $userId]);

        if (is_null($userDto)) {
            throw RuntimeException::create('Login user is missing.')->withContext(['userId' => $userId]);
        }

        // TODO: Response DTO should be used
        $userResponse = [
            'id' => $userDto->userId,
            'name' => $userDto->name,
        ];

        return $userResponse;
    }
}
