<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\Uid;

use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;

final class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): Uuid
    {
        return Uuid::v4();
    }
}
