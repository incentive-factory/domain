<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Path;

use IncentiveFactory\Game\Path\GetPathsByPlayer\PlayerPaths;
use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\Player;
use IncentiveFactory\Game\Tests\QueryTestCase;
use Symfony\Component\Uid\Ulid;

final class GetPathsByPlayerTest extends QueryTestCase
{
    public function testShouldReturnListOfPath(): void
    {
        $player = Player::create(Ulid::fromString('01GBJK7XV3YXQ51EHN9G5DAMYN'));

        /** @var array<array-key, Path> $paths */
        $paths = $this->queryBus->fetch(new PlayerPaths($player));

        self::assertCount(2, $paths);
        self::assertContainsOnlyInstancesOf(Path::class, $paths);
    }
}
