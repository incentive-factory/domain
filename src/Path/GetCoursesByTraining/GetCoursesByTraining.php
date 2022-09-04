<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetCoursesByTraining;

use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

final class GetCoursesByTraining implements QueryHandler
{
    public function __construct(private CourseGateway $courseGateway)
    {
    }

    /**
     * @return array<array-key, Course>
     */
    public function __invoke(TrainingCourses $trainingCourses): array
    {
        return $this->courseGateway->getCoursesByTraining($trainingCourses->training);
    }
}
