<?php

declare(strict_types=1);

use App\Adapter\Http\Controllers;
use App\Adapter\Http\Middlewares\RejectUnauthenticatedRequest;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();
    assert($container !== null);
    $app->get('/', [Controllers\HomeController::class, 'index']);
    $app->post('/register', [Controllers\UserController::class, 'register']);
    $app->post('/login', [Controllers\LoginController::class, 'login']);
    $app->get('/user', [Controllers\UserController::class, 'user'])->add($container->get(RejectUnauthenticatedRequest::class));
};
