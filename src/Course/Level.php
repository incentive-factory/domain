<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course;

enum Level: int
{
    case Easy = 1;
    case Medium = 2;
    case Hard = 3;
}
