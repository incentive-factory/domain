<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use DateTimeImmutable;
use IncentiveFactory\Domain\Path\BeginCourse\BeginningOfCourse;
use IncentiveFactory\Domain\Path\BeginCourse\CourseAlreadyBeganException;
use IncentiveFactory\Domain\Path\BeginCourse\CourseBegan;
use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseLogRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPathRepository;
use IncentiveFactory\Domain\Tests\CommandTestCase;

final class BeginCourseTest extends CommandTestCase
{
    public function testShouldBeginCourse(): void
    {
        /** @var CourseGateway $courseGateway */
        $courseGateway = $this->container->get(CourseGateway::class);

        /** @var Course $course */
        $course = $courseGateway->getCourseBySlug('course-4');

        /** @var InMemoryPathRepository $pathGateway */
        $pathGateway = $this->container->get(PathGateway::class);

        $path = $pathGateway->paths['01GBXF8EPC06PV81J70Z0ACKCC'];

        $this->commandBus->execute(new BeginningOfCourse($path, $course));

        /** @var InMemoryCourseLogRepository $courseLogGateway */
        $courseLogGateway = $this->container->get(CourseLogGateway::class);

        self::assertTrue($courseLogGateway->hasAlreadyBegan($path, $course));

        /** @var array<array-key, CourseLog> $courseLogs */
        $courseLogs = array_values(
            array_filter(
                $courseLogGateway->courseLogs,
                static fn (CourseLog $courseLog) => $courseLog->path()->id()->equals($path->id()) && $courseLog->course()->id()->equals($course->id()),
            )
        );

        self::assertCount(1, $courseLogs);

        $courseLog = $courseLogs[0];

        self::assertEquals($path, $courseLog->path());
        self::assertEquals($course, $courseLog->course());
        self::assertNull($courseLog->completedAt());
        self::assertFalse($courseLog->hasCompleted());
        self::assertLessThan(new DateTimeImmutable(), $courseLog->beganAt());
        self::assertTrue($this->eventBus->hasDispatched(CourseBegan::class));
    }

    public function testShouldRaiseAnExceptionDueToACourseLogAlreadyBegan(): void
    {
        /** @var CourseGateway $courseGateway */
        $courseGateway = $this->container->get(CourseGateway::class);

        /** @var Course $course */
        $course = $courseGateway->getCourseBySlug('course-1');

        /** @var InMemoryPathRepository $pathGateway */
        $pathGateway = $this->container->get(PathGateway::class);

        $path = $pathGateway->paths['01GBXF8ATAE03HY5ZC3ES90122'];

        self::expectException(CourseAlreadyBeganException::class);

        $this->commandBus->execute(new BeginningOfCourse($path, $course));
    }
}
