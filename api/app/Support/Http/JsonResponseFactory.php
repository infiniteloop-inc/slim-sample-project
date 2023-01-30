<?php

declare(strict_types=1);

namespace App\Support\Http;

use JsonSerializable;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\ResponseFactory;

class JsonResponseFactory
{
    public static function create(string $data): ResponseInterface
    {
        $body = (new Psr17Factory())->createStream($data);

        return (new ResponseFactory())->createResponse()
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body);
    }

    public static function createWithSerialize(array|JsonSerializable $data): ResponseInterface
    {
        return static::create(static::serialize($data));
    }

    public static function empty(): ResponseInterface
    {
        return static::create(static::serialize([]));
    }

    protected static function serialize(array|JsonSerializable $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
