<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\CompleteCourse;

use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Shared\Command\Command;

final class CompletingOfCourse implements Command
{
    public function __construct(public CourseLog $courseLog)
    {
    }
}
