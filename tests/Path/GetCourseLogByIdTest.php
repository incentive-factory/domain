<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use Generator;
use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Path\GetCourseLogById\CourseLogId;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Tests\QueryTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class GetCourseLogByIdTest extends QueryTestCase
{
    public function testShouldReturnAPathByItsId(): void
    {
        /** @var ?CourseLog $courseLog */
        $courseLog = $this->queryBus->fetch(new CourseLogId('01GBYPDXW2T78ZAKSANH3G810V'));

        self::assertInstanceOf(CourseLog::class, $courseLog);
    }

    public function testShouldReturnNull(): void
    {
        /** @var ?CourseLog $courseLog */
        $courseLog = $this->queryBus->fetch(new CourseLogId('01GC475QZKWB0HAN5DY48D3NET'));

        self::assertNull($courseLog);
    }

    /**
     * @dataProvider providePathSlugs
     */
    public function testShouldFailedDueToAInvalidPathId(CourseLogId $courseLogId): void
    {
        self::expectException(ValidationFailedException::class);

        $this->queryBus->fetch($courseLogId);
    }

    /**
     * @return Generator<string, array<array-key, CourseLogId>>
     */
    public function providePathSlugs(): Generator
    {
        yield 'blank id' => [new CourseLogId('')];
        yield 'invalid id' => [new CourseLogId('fail')];
    }
}
