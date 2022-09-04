<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\CheckIfCourseHasBegun;

use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Shared\Query\Query;

final class CourseBegan implements Query
{
    public function __construct(public Path $path, public Course $course)
    {
    }
}
