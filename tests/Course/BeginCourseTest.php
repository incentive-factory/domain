<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Course;

use DateTimeImmutable;
use IncentiveFactory\Domain\Course\BeginCourse\BeginningOfCourse;
use IncentiveFactory\Domain\Course\BeginCourse\CourseAlreadyBeganException;
use IncentiveFactory\Domain\Course\BeginCourse\CourseBegan;
use IncentiveFactory\Domain\Course\Course;
use IncentiveFactory\Domain\Course\CourseGateway;
use IncentiveFactory\Domain\Course\CourseLog;
use IncentiveFactory\Domain\Course\CourseLogGateway;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseLogRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Domain\Tests\CommandTestCase;

final class BeginCourseTest extends CommandTestCase
{
    public function testShouldBeginCourse(): void
    {
        /** @var CourseGateway $courseGateway */
        $courseGateway = $this->container->get(CourseGateway::class);

        /** @var Course $course */
        $course = $courseGateway->findOneBySlug('course-1');

        $player = InMemoryPlayerRepository::createPlayer(1, '01GBJK7XV3YXQ51EHN9G5DAMYN');

        $this->commandBus->execute(new BeginningOfCourse($player, $course));

        /** @var InMemoryCourseLogRepository $courseLogGateway */
        $courseLogGateway = $this->container->get(CourseLogGateway::class);

        self::assertTrue($courseLogGateway->hasAlreadyBegan($player, $course));

        /** @var array<array-key, CourseLog> $courseLogs */
        $courseLogs = array_values(
            array_filter(
                $courseLogGateway->courseLogs,
                static fn (CourseLog $courseLog) => $courseLog->player()->id()->equals($player->id()) && $courseLog->course()->id()->equals($course->id()),
            )
        );

        self::assertCount(1, $courseLogs);

        $courseLog = $courseLogs[0];

        self::assertEquals($player, $courseLog->player());
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
        $course = $courseGateway->findOneBySlug('course-1');

        $player = InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY');

        self::expectException(CourseAlreadyBeganException::class);

        $this->commandBus->execute(new BeginningOfCourse($player, $course));
    }
}
