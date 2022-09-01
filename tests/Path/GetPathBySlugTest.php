<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Path;

use DateTimeImmutable;
use Generator;
use IncentiveFactory\Game\Path\GetPathBySlug\PathSlug;
use IncentiveFactory\Game\Path\Level;
use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Tests\QueryTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class GetPathBySlugTest extends QueryTestCase
{
    public function testShouldReturnAPathByItsSlug(): void
    {
        /** @var ?Path $path */
        $path = $this->queryBus->fetch(new PathSlug('path-1'));

        self::assertInstanceOf(Path::class, $path);
        self::assertSame('path-1', $path->slug());
        self::assertSame('Path 1', $path->name());
        self::assertSame('Description 1', $path->description());
        self::assertSame(Level::Easy, $path->level());
        self::assertSame('Prerequisites 1', $path->prerequisites());
        self::assertSame('Skills 1', $path->skills());
        self::assertSame('image.png', $path->image());
        self::assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $path->publishedAt());
        self::assertSame('01GBWW5FJJ0G3YK3RJM6VWBZBG', (string) $path->id());
    }

    public function testShouldReturnNull(): void
    {
        /** @var ?Path $path */
        $path = $this->queryBus->fetch(new PathSlug('path-0'));

        self::assertNull($path);
    }

    /**
     * @dataProvider providePathSlugs
     */
    public function testShouldFailedDueToAInvalidPathSlug(PathSlug $pathSlug): void
    {
        self::expectException(ValidationFailedException::class);

        $this->queryBus->fetch($pathSlug);
    }

    /**
     * @return Generator<string, array<array-key, PathSlug>>
     */
    public function providePathSlugs(): Generator
    {
        yield 'blank slug' => [new PathSlug('')];
    }
}
