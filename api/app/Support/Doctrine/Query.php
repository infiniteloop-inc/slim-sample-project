<?php

declare(strict_types=1);

namespace App\Support\Doctrine;

use App\Support\Database\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

/**
 * @template T of object
 * @template-implements  QueryInterface<T>
 */
abstract class Query implements QueryInterface
{
    /**
     * @var ObjectRepository<T>
     */
    protected $repos;

    public function __construct(
        protected EntityManagerInterface $em
    ) {
        $this->repos = $em->getRepository($this->getEntityClassName());
    }

    /**
     * @return class-string<T>
     */
    abstract public function getEntityClassName(): string;

    /**
     * @param array $criteria
     * @return object|null The object.
     * @psalm-return T|null
     */
    public function findOneBy(array $criteria): ?object
    {
        return $this->repos->findOneBy($criteria);
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @return array<int, object> The objects.
     * @psalm-return T[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this->repos->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param T $dto
     * @return void
     */
    public function persist(object $dto): void
    {
        $this->em->persist($dto);
    }

    protected function createQueryBuilder(string $alias, ?string $indexBy = null): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select($alias)
            ->from($this->getEntityClassName(), $alias, $indexBy);
    }
}
