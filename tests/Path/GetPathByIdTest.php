<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use Generator;
use IncentiveFactory\Domain\Path\GetPathById\PathId;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Tests\QueryTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class GetPathByIdTest extends QueryTestCase
{
    public function testShouldReturnAPathByItsId(): void
    {
        /** @var ?Path $path */
        $path = $this->queryBus->fetch(new PathId('01GBXF8ATAE03HY5ZC3ES90122'));

        self::assertInstanceOf(Path::class, $path);
    }

    public function testShouldReturnNull(): void
    {
        /** @var ?Path $path */
        $path = $this->queryBus->fetch(new PathId('01GC475QZKWB0HAN5DY48D3NET'));

        self::assertNull($path);
    }

    /**
     * @dataProvider providePathSlugs
     */
    public function testShouldFailedDueToAInvalidPathId(PathId $pathId): void
    {
        self::expectException(ValidationFailedException::class);

        $this->queryBus->fetch($pathId);
    }

    /**
     * @return Generator<string, array<array-key, PathId>>
     */
    public function providePathSlugs(): Generator
    {
        yield 'blank id' => [new PathId('')];
        yield 'invalid id' => [new PathId('fail')];
    }
}
