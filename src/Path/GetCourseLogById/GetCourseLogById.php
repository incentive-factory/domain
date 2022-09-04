<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetCourseLogById;

use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

final class GetCourseLogById implements QueryHandler
{
    public function __construct(private CourseLogGateway $pathGateway)
    {
    }

    public function __invoke(CourseLogId $query): ?CourseLog
    {
        return $this->pathGateway->getCourseLogById($query->id);
    }
}
