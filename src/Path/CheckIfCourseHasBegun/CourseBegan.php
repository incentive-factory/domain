<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\CheckIfCourseHasBegun;

use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Shared\Query\Query;

final class CourseBegan implements Query
{
    public function __construct(public Player $player, public Course $course)
    {
    }
}
