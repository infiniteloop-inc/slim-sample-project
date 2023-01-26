<?php

declare(strict_types=1);

namespace App\Support\Doctrine;

use App\Support\Database\TransactionInterface;
use Doctrine\ORM\EntityManagerInterface;

class Transaction implements TransactionInterface
{
    public function __construct(
        public EntityManagerInterface $em
    ) {
    }

    public function handle(callable $func): void
    {
        $this->em->wrapInTransaction($func);
    }

    public function begin(): void
    {
        $this->em->beginTransaction();
    }

    public function commit(): void
    {
        $this->em->commit();
    }

    public function rollback(): void
    {
        $this->em->rollback();
    }
}
