<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\CheckIfPathHasBegun;

use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;
use IncentiveFactory\Domain\Shared\Query\Query;

final class PathBegan implements Query
{
    public function __construct(public PlayerInterface $player, public Training $training)
    {
    }
}
