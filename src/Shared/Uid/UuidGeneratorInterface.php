<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Shared\Uid;

use Symfony\Component\Uid\Uuid;

interface UuidGeneratorInterface
{
    public function generate(): Uuid;
}
