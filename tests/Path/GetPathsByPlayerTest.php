<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use IncentiveFactory\Domain\Path\GetPathsByPlayer\PlayerPaths;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Domain\Tests\QueryTestCase;

final class GetPathsByPlayerTest extends QueryTestCase
{
    public function testShouldReturnListOfPath(): void
    {
        $player = InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY');

        /** @var array<array-key, Path> $paths */
        $paths = $this->queryBus->fetch(new PlayerPaths($player));

        self::assertCount(2, $paths);
        self::assertContainsOnlyInstancesOf(Path::class, $paths);
    }
}
