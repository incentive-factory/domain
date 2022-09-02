<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\BeginTraining;

use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Shared\Command\Command;
use IncentiveFactory\Game\Shared\Entity\PlayerInterface;

final class BeginningOfTraining implements Command
{
    public function __construct(public PlayerInterface $player, public Training $training)
    {
    }
}