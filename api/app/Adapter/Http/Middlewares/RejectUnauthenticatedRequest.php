<?php

declare(strict_types=1);

namespace App\Adapter\Http\Middlewares;

use App\Domain\User\Auth\AuthenticationService;
use App\Exceptions\UnauthenticatedException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class RejectUnauthenticatedRequest implements MiddlewareInterface
{
    public function __construct(
        private AuthenticationService $authenticationService
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        if (!$this->authenticationService->isLoggedIn()) {
            throw new UnauthenticatedException();
        }

        return $handler->handle($request);
    }
}
