<?php

declare(strict_types=1);

namespace App\Domain\User;

class UserRepository
{
    public function __construct(
        protected UserQuery $userQuery
    ) {
    }

    public function resolve(string $userId): ?User
    {
        $userDto = $this->userQuery->findOneBy(['userId' => $userId]);
        if ($userDto === null) {
            return null;
        }
        return new User($userDto);
    }

    public function persist(User $user): void
    {
        $this->userQuery->persist($user->userDto);
    }
}
