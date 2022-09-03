<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course\BeginCourse;

use IncentiveFactory\Domain\Course\Course;
use IncentiveFactory\Domain\Shared\Command\Command;
use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;

final class BeginningOfCourse implements Command
{
    public function __construct(public PlayerInterface $player, public Course $course)
    {
    }
}
