<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * @psalm-consistent-constructor
 */
abstract class BaseException extends Exception
{
    protected array $context;

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public static function create(string $message = "", int $code = 0, Throwable $previous = null, array $context = []): self
    {
        return new static($message, $code, $previous, $context);
    }

    public function withCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function withContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
