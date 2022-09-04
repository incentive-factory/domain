<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetCoursesByTraining;

use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Shared\Query\Query;

final class TrainingCourses implements Query
{
    public function __construct(public Training $training)
    {
    }
}
