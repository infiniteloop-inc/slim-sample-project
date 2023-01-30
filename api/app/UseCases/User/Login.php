<?php

declare(strict_types=1);

namespace App\UseCases\User;

use App\Domain\User\Auth\AuthenticationService;

class Login
{
    public function __construct(
        private AuthenticationService $authenticationService
    ) {
    }
    public function execute(string $authToken): array
    {
        $this->authenticationService->attemptLogin($authToken);

        return [];
    }
}
