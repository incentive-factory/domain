<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Course;

use DateTimeImmutable;
use IncentiveFactory\Domain\Course\CompleteCourse\CompletingOfCourse;
use IncentiveFactory\Domain\Course\CompleteCourse\CourseAlreadyCompletedException;
use IncentiveFactory\Domain\Course\CompleteCourse\CourseCompleted;
use IncentiveFactory\Domain\Course\CourseLogGateway;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseLogRepository;
use IncentiveFactory\Domain\Tests\CommandTestCase;

final class CompleteCourseTest extends CommandTestCase
{
    public function testShouldCompleteCourse(): void
    {
        /** @var InMemoryCourseLogRepository $courseLogGateway */
        $courseLogGateway = $this->container->get(CourseLogGateway::class);

        $courseLog = $courseLogGateway->courseLogs['01GBYPE9N8HTJQ4A2G1Y96FATW'];

        $this->commandBus->execute(new CompletingOfCourse($courseLog));

        self::assertTrue($courseLog->hasCompleted());
        self::assertLessThan(new DateTimeImmutable(), $courseLog->completedAt());
        self::assertTrue($this->eventBus->hasDispatched(CourseCompleted::class));
    }

    public function testShouldRaiseAnExceptionDueToACourseLogAlreadyCompleted(): void
    {
        /** @var InMemoryCourseLogRepository $courseLogGateway */
        $courseLogGateway = $this->container->get(CourseLogGateway::class);

        $courseLog = $courseLogGateway->courseLogs['01GBYPE4FW8J8TN21CA3AFSXQH'];

        self::expectException(CourseAlreadyCompletedException::class);

        $this->commandBus->execute(new CompletingOfCourse($courseLog));
    }
}
