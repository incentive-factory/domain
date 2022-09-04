<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use IncentiveFactory\Domain\Path\BeginCourse\BeginningOfCourse;
use IncentiveFactory\Domain\Path\BeginTraining\BeginningOfTraining;
use IncentiveFactory\Domain\Path\CompleteCourse\CompletingOfCourse;
use IncentiveFactory\Domain\Path\CompleteCourse\CourseCompleted;
use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Path\TrainingGateway;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseLogRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPathRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryTrainingRepository;
use IncentiveFactory\Domain\Tests\CommandTestCase;

final class CompletePathTest extends CommandTestCase
{
    private InMemoryCourseRepository $courseGateway;

    private InMemoryCourseLogRepository $courseLogGateway;

    private InMemoryPathRepository $pathGateway;

    private InMemoryTrainingRepository $trainingGateway;

    private InMemoryPlayerRepository $playerGateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->courseGateway = $this->container->get(CourseGateway::class);
        $this->pathGateway = $this->container->get(PathGateway::class);
        $this->courseLogGateway = $this->container->get(CourseLogGateway::class);
        $this->trainingGateway = $this->container->get(TrainingGateway::class);
        $this->playerGateway = $this->container->get(PlayerGateway::class);
    }

    public function testShouldCompletePath(): void
    {
        /** @var Player $player */
        $player = $this->playerGateway->getPlayerByEmail('player+1@email.com');

        /** @var Training $training */
        $training = $this->trainingGateway->getTrainingBySlug('training-1');

        $this->commandBus->execute(new BeginningOfTraining($player, $training));

        $paths = $this->pathGateway->getPathsByPlayer($player);

        $path = $paths[0];

        $this->beginAndCompleteCourse($path, 'course-1');
        self::assertTrue($this->eventBus->hasDispatched(CourseCompleted::class));

        $this->beginAndCompleteCourse($path, 'course-2');
        self::assertTrue($this->eventBus->hasDispatched(CourseCompleted::class));

        self::assertNotNull($path->completedAt());
        self::assertTrue($path->isCompleted());
    }

    private function beginAndCompleteCourse(Path $path, string $courseSlug): void
    {
        /** @var Course $course */
        $course = $this->courseGateway->getCourseBySlug($courseSlug);

        $this->commandBus->execute(new BeginningOfCourse($path, $course));

        $courseLogs = array_values(
            array_filter(
                $this->courseLogGateway->courseLogs,
                fn (CourseLog $courseLog) => (string) $courseLog->path()->id() === (string) $path->id()
            )
        );

        $courseLog = $courseLogs[count($courseLogs) - 1];

        $this->commandBus->execute(new CompletingOfCourse($courseLog));
    }
}
