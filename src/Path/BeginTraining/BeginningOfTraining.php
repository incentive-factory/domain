<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\BeginTraining;

use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Shared\Command\Command;
use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;

final class BeginningOfTraining implements Command
{
    public function __construct(public PlayerInterface $player, public Training $training)
    {
    }
}
