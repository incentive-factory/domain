<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\BeginCourse;

use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Shared\EventDispatcher\Event;

final class CourseBegan implements Event
{
    public function __construct(public CourseLog $courseLog)
    {
    }
}
