<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Support\Doctrine\ArraySerializable;
use App\Support\Doctrine\HasTimestamps;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table(name: "tbl_user")]
#[UniqueConstraint(name: "auth_token", columns: ["auth_token"])]
#[HasLifecycleCallbacks]
class UserDto
{
    use ArraySerializable;
    use HasTimestamps;

    public function __construct(
        #[Id]
        #[Column(type: "string", length: 63)]
        public string $userId = '',
        #[Column(type: "string", length: 63)]
        public string $name = '',
        #[Column(type: "string", length: 63)]
        public string $authToken = '',
    ) {
    }
}
