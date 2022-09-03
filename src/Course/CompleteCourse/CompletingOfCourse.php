<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course\CompleteCourse;

use IncentiveFactory\Game\Course\CourseLog;
use IncentiveFactory\Game\Shared\Command\Command;

final class CompletingOfCourse implements Command
{
    public function __construct(public CourseLog $courseLog)
    {
    }
}
