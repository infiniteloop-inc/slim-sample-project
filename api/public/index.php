<?php

declare(strict_types=1);

use App\Adapter\Http\Handlers\ErrorHandler;
use App\Support\Logger\AppLogger;
use DI\Bridge\Slim\Bridge;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Middleware\ErrorMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . "//..//");
$dotenv->load();

// setup dependencies
$container = require __DIR__ . '/../config/dependencies.php';

$app = Bridge::create($container);

// setup logger (initializable singleton)
AppLogger::initialize($container->get(LoggerInterface::class));

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hello world!");
    return $response;
});

// Add error handling middleware
$displayErrorDetails = true;
$logErrors = true;
$logErrorDetails = true;
$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();
$errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, $displayErrorDetails, $logErrors, $logErrorDetails);
$errorHandler = new ErrorHandler($callableResolver, $responseFactory);
$errorMiddleware->setDefaultErrorHandler($errorHandler);
$app->add($errorMiddleware);

$app->run();
