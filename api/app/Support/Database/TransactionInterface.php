<?php

declare(strict_types=1);

namespace App\Support\Database;

interface TransactionInterface
{
    public function handle(callable $func): void;
    public function begin(): void;
    public function commit(): void;
    public function rollback(): void;
}
