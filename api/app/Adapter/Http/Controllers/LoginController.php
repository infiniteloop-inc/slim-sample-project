<?php

declare(strict_types=1);

namespace App\Adapter\Http\Controllers;

use App\Support\Http\JsonResponseFactory;
use App\UseCases\User\Login;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

class LoginController extends BaseController
{
    public function __construct(
        private Login $login,
    ) {
    }

    public function login(Request $request): Response
    {
        $params = $request->getParsedBody();
        if (!isset($params['authToken'])) {
            throw new HttpBadRequestException($request);
        }
        $authToken = (string)$params['authToken'];

        $result = $this->login->execute($authToken);
        return JsonResponseFactory::createWithSerialize($result);
    }
}
