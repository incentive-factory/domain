<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course\GetCourseBySlug;

use IncentiveFactory\Game\Course\Course;
use IncentiveFactory\Game\Course\CourseGateway;
use IncentiveFactory\Game\Shared\Query\QueryHandler;

final class GetCourseBySlug implements QueryHandler
{
    public function __construct(private CourseGateway $courseGateway)
    {
    }

    public function __invoke(CourseSlug $query): ?Course
    {
        return $this->courseGateway->findOneBySlug($query->slug);
    }
}
