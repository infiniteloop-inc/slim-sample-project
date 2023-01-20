<?php

declare(strict_types=1);

use App\Adapter\Http\Controllers;
use Slim\App;

return function (App $app) {
    $app->get('/', [Controllers\HomeController::class, 'index']);
};
