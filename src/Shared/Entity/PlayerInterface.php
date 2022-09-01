<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Shared\Entity;

use Symfony\Component\Uid\Ulid;

interface PlayerInterface
{
    public function id(): Ulid;
}
