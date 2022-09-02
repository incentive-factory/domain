<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course\BeginCourse;

use IncentiveFactory\Game\Course\Course;
use IncentiveFactory\Game\Shared\Command\Command;
use IncentiveFactory\Game\Shared\Entity\PlayerInterface;

final class BeginningOfCourse implements Command
{
    public function __construct(public PlayerInterface $player, public Course $course)
    {
    }
}
