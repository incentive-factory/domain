<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Path;

use IncentiveFactory\Game\Path\GetPathsByPlayer\PlayerPaths;
use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Game\Tests\QueryTestCase;

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
