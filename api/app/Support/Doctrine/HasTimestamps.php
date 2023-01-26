<?php

declare(strict_types=1);

namespace App\Support\Doctrine;

use Cake\Chronos\Chronos;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait HasTimestamps
{
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public ?DateTimeImmutable $createdAt = null;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    public ?DateTimeImmutable $updatedAt = null;

    protected ?DateTimeImmutable $now = null;

    /**
     * @ORM\PrePersist
     */
    public function setPersistDatetime(): void
    {
        $now = $this->now ?: Chronos::now();

        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAt(): void
    {
        $this->updatedAt = $this->now ?: Chronos::now();
    }
}
