<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Application\Uid;

use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;
use Symfony\Component\Uid\Ulid;

final class UlidGenerator implements UlidGeneratorInterface
{
    public function generate(): Ulid
    {
        return new Ulid();
    }
}
