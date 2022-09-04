<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\BeginCourse;

use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Shared\Command\Command;

final class BeginningOfCourse implements Command
{
    public function __construct(public Path $path, public Course $course)
    {
    }
}
