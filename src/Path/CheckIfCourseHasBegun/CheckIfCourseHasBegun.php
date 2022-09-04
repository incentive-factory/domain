<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\CheckIfCourseHasBegun;

use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

final class CheckIfCourseHasBegun implements QueryHandler
{
    public function __construct(private CourseLogGateway $courseLogGateway)
    {
    }

    public function __invoke(CourseBegan $query): bool
    {
        return $this->courseLogGateway->hasAlreadyBegan($query->player, $query->course);
    }
}
