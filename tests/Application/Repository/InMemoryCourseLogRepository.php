<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Application\Repository;

use DateTimeImmutable;
use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Path\Path;
use Symfony\Component\Uid\Ulid;

final class InMemoryCourseLogRepository implements CourseLogGateway
{
    /**
     * @var array<string, CourseLog>
     */
    public array $courseLogs = [];

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        $this->courseLogs = [
            '01GBYPDXW2T78ZAKSANH3G810V' => CourseLog::create(
                id: Ulid::fromString('01GBYPDXW2T78ZAKSANH3G810V'),
                path: InMemoryPathRepository::createPath(
                    id: '01GBXF8ATAE03HY5ZC3ES90122',
                    player: InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY'),
                    training: InMemoryTrainingRepository::createTraining(1, '01GBWW5FJJ0G3YK3RJM6VWBZBG')
                ),
                course: InMemoryCourseRepository::createCourse(
                    1,
                    '01GBYMQQK3TY08FEVA0GTJ4QZM',
                    InMemoryTrainingRepository::createTraining(1, '01GBWW5FJJ0G3YK3RJM6VWBZBG')
                ),
                beganAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                completedAt: new DateTimeImmutable('2021-01-02 00:00:00')
            ),
            '01GBYPE4FW8J8TN21CA3AFSXQH' => CourseLog::create(
                id: Ulid::fromString('01GBYPE4FW8J8TN21CA3AFSXQH'),
                path: InMemoryPathRepository::createPath(
                    id: '01GBXF8ATAE03HY5ZC3ES90122',
                    player: InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY'),
                    training: InMemoryTrainingRepository::createTraining(1, '01GBWW5FJJ0G3YK3RJM6VWBZBG')
                ),
                course: InMemoryCourseRepository::createCourse(
                    2,
                    '01GBYN0SWAMB7N272PW7G1VDF0',
                    InMemoryTrainingRepository::createTraining(1, '01GBWW5FJJ0G3YK3RJM6VWBZBG')
                ),
                beganAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                completedAt: new DateTimeImmutable('2021-01-02 00:00:00')
            ),
            '01GBYPE9N8HTJQ4A2G1Y96FATW' => CourseLog::create(
                id: Ulid::fromString('01GBYPE9N8HTJQ4A2G1Y96FATW'),
                path: InMemoryPathRepository::createPath(
                    id: '01GBXF8EPC06PV81J70Z0ACKCC',
                    player: InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY'),
                    training: InMemoryTrainingRepository::createTraining(2, '01GBWW5JHNPEXD8S0J5HPT97S2')
                ),
                course: InMemoryCourseRepository::createCourse(
                    3,
                    '01GBYN0XFWE0HSS8TT5YNXVH6W',
                    InMemoryTrainingRepository::createTraining(1, '01GBWW5JHNPEXD8S0J5HPT97S2')
                ),
                beganAt: new DateTimeImmutable('2021-01-01 00:00:00')
            ),
        ];
    }

    public function begin(CourseLog $courseLog): void
    {
        $this->courseLogs[(string) $courseLog->id()] = $courseLog;
    }

    public function complete(CourseLog $courseLog): void
    {
        $this->courseLogs[(string) $courseLog->id()] = $courseLog;
    }

    public function hasAlreadyBegan(Path $path, Course $course): bool
    {
        foreach ($this->courseLogs as $courseLog) {
            if ($courseLog->path()->id()->equals($path->id()) && $courseLog->course()->id()->equals($course->id())) {
                return true;
            }
        }

        return false;
    }

    public function countCoursesCompletedByPath(Path $path): int
    {
        return count(
            array_filter(
                $this->courseLogs,
                fn (CourseLog $courseLog) => (string) $courseLog->path()->id() === (string) $path->id() && $courseLog->hasCompleted()
            )
        );
    }
}
