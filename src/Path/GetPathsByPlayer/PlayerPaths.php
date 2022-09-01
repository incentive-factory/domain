<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\GetPathsByPlayer;

use IncentiveFactory\Game\Path\Player;
use IncentiveFactory\Game\Shared\Query\Query;

final class PlayerPaths implements Query
{
    public function __construct(public Player $player)
    {
    }
}
