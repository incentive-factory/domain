<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetCourseBySlug;

use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

final class GetCourseBySlug implements QueryHandler
{
    public function __construct(private CourseGateway $courseGateway)
    {
    }

    public function __invoke(CourseSlug $query): ?Course
    {
        return $this->courseGateway->getCourseBySlug($query->slug);
    }
}
