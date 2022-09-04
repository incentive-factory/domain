<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course\GetCourseBySlug;

use IncentiveFactory\Domain\Course\Course;
use IncentiveFactory\Domain\Course\CourseGateway;
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
