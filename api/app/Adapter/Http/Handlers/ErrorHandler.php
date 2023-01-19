<?php

declare(strict_types=1);

namespace App\Adapter\Http\Handlers;

use App\Exceptions\UnauthenticatedException;
use App\Support\Logger\AppLogger;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Exception;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ErrorHandler extends SlimErrorHandler
{
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const INSUFFICIENT_PRIVILEGES = 'INSUFFICIENT_PRIVILEGES';
    public const NOT_ALLOWED = 'NOT_ALLOWED';
    public const NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';
    public const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    public const SERVER_ERROR = 'SERVER_ERROR';
    public const UNAUTHENTICATED = 'UNAUTHENTICATED';

    public function logError(string $error): void
    {
        AppLogger::get()->error($error);
    }

    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        $statusCode = 500;
        $type = self::SERVER_ERROR;
        $description = 'An internal error has occurred while processing your request.';

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $description = $exception->getMessage();

            if ($exception instanceof HttpNotFoundException) {
                $type = self::RESOURCE_NOT_FOUND;
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $type = self::NOT_ALLOWED;
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpForbiddenException) {
                $type = self::UNAUTHENTICATED;
            } elseif ($exception instanceof HttpBadRequestException) {
                $type = self::BAD_REQUEST;
            } elseif ($exception instanceof HttpNotImplementedException) {
                $type = self::NOT_IMPLEMENTED;
            }
        }

        if ($exception instanceof UnauthenticatedException) {
            $statusCode = 401;
            $type = self::UNAUTHENTICATED;
        }

        if (
            !($exception instanceof HttpException)
            && $exception instanceof Exception
            && $this->displayErrorDetails
        ) {
            // TODO: Should not be displayed to end users on a productin environment
            $description = $exception->getMessage() . $exception->getTraceAsString();
        }

        $error = [
            'status' => $statusCode,
            'error' => [
                'type' => $type,
                'detail' => $description,
            ],
        ];

        $payload = json_encode($error, JSON_PRETTY_PRINT);
        if ($payload === false) {
            $payload = '{"error": "Json Encoding Error"}';
            AppLogger::get()->error("Failed to encode an error response to JSON. StatusCode=" . $statusCode);
        }

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
