<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course\CompleteCourse;

use IncentiveFactory\Domain\Course\CourseLog;
use IncentiveFactory\Domain\Shared\Command\Command;

final class CompletingOfCourse implements Command
{
    public function __construct(public CourseLog $courseLog)
    {
    }
}
