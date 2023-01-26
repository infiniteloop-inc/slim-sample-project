<?php

declare(strict_types=1);

namespace App\Support\Database;

/**
 * @template T of object
 */
interface QueryInterface
{
    /**
     * @return class-string<T>
     */
    public function getEntityClassName(): string;

    /**
     * @param array $criteria
     * @return T|null
     */
    public function findOneBy(array $criteria): ?object;

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @return T[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param T $dto
     * @return void
     */
    public function persist(object $dto): void;
}
