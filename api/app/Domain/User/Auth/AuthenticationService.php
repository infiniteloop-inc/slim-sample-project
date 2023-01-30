<?php

declare(strict_types=1);

namespace App\Domain\User\Auth;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Exceptions\UnauthenticatedException;
use App\Support\Logger\AppLogger;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthenticationService
{
    private const SESSION_USERID_KEY = 'userId';

    public function __construct(
        protected UserRepository $userRepository,
        protected SessionInterface $session
    ) {
    }

    public function attemptLogin(string $authToken): User
    {
        $user = $this->userRepository->resolveByAuthToken($authToken);

        if (is_null($user)) {
            $this->session->invalidate();
            AppLogger::get()->debug('Failed to login.', ['authToken' => $authToken]);
            throw new UnauthenticatedException();
        }

        // Prevent session fixation attacks
        $this->session->migrate(true);

        $this->session->set(self::SESSION_USERID_KEY, $user->id());

        // TODO: Update authToken

        return $user;
    }

    public function fetchLoggedInUser(): ?User
    {
        $user = null;
        if ($userId = $this->session->get(self::SESSION_USERID_KEY)) {
            $user = $this->userRepository->resolve($userId);
        }
        return $user;
    }

    public function isLoggedIn(): bool
    {
        return ($this->session->get(self::SESSION_USERID_KEY) !== null);
    }
}
