<?php

declare(strict_types=1);

namespace App\Adapter\Http\Middlewares;

use App\Support\Logger\AppLogger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Throwable;

final class StartSession implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session
    ) {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        try {
            $this->session->start();
        } catch (Throwable $e) {
            $params = in_array($request->getMethod(), ['GET', 'HEAD'], true) ? $request->getQueryParams() : (array)$request->getParsedBody();
            AppLogger::get()->error('Failed to start session.', ['request' => $request->getMethod() . ' ' . $request->getRequestTarget(), 'params' => $params]);
            throw $e;
        }

        $request = $request->withAttribute('session', $this->session);

        $response = $handler->handle($request);

        $this->session->save();

        return $response;
    }
}
