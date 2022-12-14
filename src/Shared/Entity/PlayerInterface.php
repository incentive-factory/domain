<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Shared\Entity;

use Symfony\Component\Uid\Ulid;

interface PlayerInterface
{
    public function id(): Ulid;
}
