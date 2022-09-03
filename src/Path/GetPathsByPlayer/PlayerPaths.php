<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetPathsByPlayer;

use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;
use IncentiveFactory\Domain\Shared\Query\Query;

final class PlayerPaths implements Query
{
    public function __construct(public PlayerInterface $player)
    {
    }
}
