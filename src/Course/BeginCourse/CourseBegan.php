<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course\BeginCourse;

use IncentiveFactory\Game\Course\CourseLog;
use IncentiveFactory\Game\Shared\Event\Event;

final class CourseBegan implements Event
{
    public function __construct(public CourseLog $courseLog)
    {
    }
}
