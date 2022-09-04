<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course\BeginCourse;

use IncentiveFactory\Domain\Course\CourseLog;
use IncentiveFactory\Domain\Shared\EventDispatcher\Event;

final class CourseBegan implements Event
{
    public function __construct(public CourseLog $courseLog)
    {
    }
}
