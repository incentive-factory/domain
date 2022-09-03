<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\BeginTraining;

use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Shared\Event\Event;

final class TrainingBegan implements Event
{
    public function __construct(public Path $path)
    {
    }
}
