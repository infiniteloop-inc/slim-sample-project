<?php

declare(strict_types=1);

namespace App\Adapter\Http\Controllers;

use App\Support\Http\JsonResponseFactory;
use App\UseCases\User\Register;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    public function __construct(
        private Register $register,
    ) {
    }

    public function register(Request $request): Response
    {
        $params = $request->getParsedBody();
        $name = $params['name'] ?? '';
        assert(is_string($name));


        $response = $this->register->execute($name);
        return JsonResponseFactory::createWithSerialize($response);
    }
}
