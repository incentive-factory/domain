<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\CompleteCourse;

use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Shared\EventDispatcher\Event;

final class CourseCompleted implements Event
{
    public function __construct(public CourseLog $courseLog)
    {
    }
}
