<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player;

interface PlayerGateway
{
    public function register(Player $player): void;
}
