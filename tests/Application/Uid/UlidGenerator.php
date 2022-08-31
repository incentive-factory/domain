<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\Uid;

use IncentiveFactory\Game\Shared\Uid\UlidGeneratorInterface;
use Symfony\Component\Uid\Ulid;

final class UlidGenerator implements UlidGeneratorInterface
{
    public function generate(): Ulid
    {
        return new Ulid();
    }
}
