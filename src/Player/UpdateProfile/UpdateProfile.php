<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\UpdateProfile;

use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Shared\Command\CommandHandler;

final class UpdateProfile implements CommandHandler
{
    public function __construct(private PlayerGateway $playerGateway)
    {
    }

    public function __invoke(Profile $profile): void
    {
        $player = $profile->player;
        $player->update($profile->email, $profile->nickname, $profile->avatar);
        $this->playerGateway->update($player);
    }
}
