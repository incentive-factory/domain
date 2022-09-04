<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetCourseLogByPathAndCourse;

use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

final class GetCourseLogByPathAndCourse implements QueryHandler
{
    public function __construct(private CourseLogGateway $courseLogGateway)
    {
    }

    public function __invoke(CourseLogPathAndCourse $query): ?CourseLog
    {
        return $this->courseLogGateway->getCourseLogByPathAndCourse($query->path, $query->course);
    }
}
