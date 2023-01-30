<?php

declare(strict_types=1);

namespace App\Adapter\Http\Middlewares;

use App\Adapter\Http\Controllers\BaseController;
use App\Domain\User\Auth\AuthenticationService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Authenticate implements MiddlewareInterface
{
    public function __construct(
        private AuthenticationService $authenticationService
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $user = $this->authenticationService->fetchLoggedInUser();

        if ($user) {
            BaseController::setLoggedInUser($user);
        }

        return $handler->handle($request);
    }
}
