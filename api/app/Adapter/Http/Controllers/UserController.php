<?php

declare(strict_types=1);

namespace App\Adapter\Http\Controllers;

use App\Support\Http\JsonResponseFactory;
use App\UseCases\User\GetUser;
use App\UseCases\User\Register;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends BaseController
{
    public function __construct(
        private GetUser $getUser,
        private Register $register,
    ) {
    }

    public function user(Request $request): Response
    {
        $userId = $this->getLoggedInUser()->id();

        $response = $this->getUser->execute($userId);
        return JsonResponseFactory::createWithSerialize($response);
    }

    public function register(Request $request): Response
    {
        if ($this->isLoggedIn()) {
            // TODO: (note) The following code has been disabled for debug
            // throw new RuntimeException('User is already registered.');
        }
        $params = $request->getParsedBody();
        $name = $params['name'] ?? '';
        assert(is_string($name));


        $response = $this->register->execute($name);
        return JsonResponseFactory::createWithSerialize($response);
    }
}
