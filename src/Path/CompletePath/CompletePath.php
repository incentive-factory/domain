<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\CompletePath;

use IncentiveFactory\Domain\Path\CompleteCourse\CourseCompleted;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Shared\EventDispatcher\EventListener;

final class CompletePath implements EventListener
{
    public function __construct(
        private CourseLogGateway $courseLogGateway,
        private CourseGateway $courseGateway,
        private PathGateway $pathGateway
    ) {
    }

    public function __invoke(CourseCompleted $courseCompleted): void
    {
        $path = $courseCompleted->courseLog->path();

        $numberOfCoursesCompleted = $this->courseLogGateway->countCoursesCompletedByPath($path);

        $numberOfCourses = $this->courseGateway->countCoursesByTraining($path->training());

        if ($numberOfCoursesCompleted === $numberOfCourses) {
            $path->complete();

            $this->pathGateway->complete($path);
        }
    }
}
