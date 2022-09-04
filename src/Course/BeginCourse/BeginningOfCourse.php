<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course\BeginCourse;

use IncentiveFactory\Domain\Course\Course;
use IncentiveFactory\Domain\Shared\Command\Command;
use IncentiveFactory\Domain\Shared\Entity\PathInterface;

final class BeginningOfCourse implements Command
{
    public function __construct(public PathInterface $path, public Course $course)
    {
    }
}
