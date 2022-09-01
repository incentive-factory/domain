<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\BeginPath;

use IncentiveFactory\Game\Path\Player;
use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Shared\Command\Command;

final class BeginningOfPath implements Command
{
    public function __construct(public Player $player, public Training $training)
    {
    }
}
