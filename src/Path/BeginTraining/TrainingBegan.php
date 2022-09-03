<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\BeginTraining;

use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Shared\Event\Event;

final class TrainingBegan implements Event
{
    public function __construct(public Path $path)
    {
    }
}
