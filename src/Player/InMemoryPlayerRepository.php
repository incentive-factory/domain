<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player;

final class InMemoryPlayerRepository implements PlayerGateway
{
    /**
     * @var array<array-key, Player>
     */
    public array $players = [];

    public function register(Player $player): void
    {
        $this->players[] = $player;
    }
}
