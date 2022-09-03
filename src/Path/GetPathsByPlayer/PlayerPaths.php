<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\GetPathsByPlayer;

use IncentiveFactory\Game\Shared\Entity\PlayerInterface;
use IncentiveFactory\Game\Shared\Query\Query;

final class PlayerPaths implements Query
{
    public function __construct(public PlayerInterface $player)
    {
    }
}
