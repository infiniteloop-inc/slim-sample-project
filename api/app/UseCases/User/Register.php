<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\User\UserFactory;
use App\Domain\User\UserRepository;
use App\Support\Database\TransactionInterface;

class Register
{
    public function __construct(
        private UserRepository $userRepository,
        private UserFactory $userFactory,
        private TransactionInterface $transaction,
    ) {
    }

    public function execute(string $name): array
    {
        $user = $this->userFactory->create($name);

        $this->transaction->handle(
            fn () => $this->userRepository->persist($user)
        );

        // TODO: Response DTO should be used
        $response = [
            'authToken' => $user->userDto->authToken,
        ];

        return $response;
    }
}
