<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Path;

use IncentiveFactory\Game\Path\GetPaths\ListOfPaths;
use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Tests\QueryTestCase;

final class GetPathsTest extends QueryTestCase
{
    public function testShouldReturnListOfPath(): void
    {
        /** @var array<array-key, Path> $paths */
        $paths = $this->queryBus->fetch(new ListOfPaths());

        self::assertCount(3, $paths);
        self::assertContainsOnlyInstancesOf(Path::class, $paths);
    }
}
