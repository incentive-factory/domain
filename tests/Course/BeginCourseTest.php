<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Course;

use DateTimeImmutable;
use IncentiveFactory\Game\Course\BeginCourse\BeginningOfCourse;
use IncentiveFactory\Game\Course\BeginCourse\CourseAlreadyBeganException;
use IncentiveFactory\Game\Course\Course;
use IncentiveFactory\Game\Course\CourseGateway;
use IncentiveFactory\Game\Course\CourseLog;
use IncentiveFactory\Game\Course\CourseLogGateway;
use IncentiveFactory\Game\Tests\Application\Repository\InMemoryCourseLogRepository;
use IncentiveFactory\Game\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Game\Tests\CommandTestCase;

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
        self::assertLessThan(new DateTimeImmutable(), $courseLog->beganAt());
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
