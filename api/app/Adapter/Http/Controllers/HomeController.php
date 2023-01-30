<?php

declare(strict_types=1);

namespace App\Adapter\Http\Controllers;

use App\Support\Http\JsonResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends BaseController
{
    public function index(Request $request): Response
    {
        return JsonResponseFactory::empty();
    }
}
