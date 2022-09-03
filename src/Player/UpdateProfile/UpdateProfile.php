<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\UpdateProfile;

use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;

final class UpdateProfile implements CommandHandler
{
    public function __construct(private PlayerGateway $playerGateway)
    {
    }

    public function __invoke(Profile $profile): void
    {
        $player = $profile->player;
        $player->update(
            email: $profile->email,
            gender: $profile->gender,
            nickname: $profile->nickname,
            avatar: $profile->avatar
        );
        $this->playerGateway->update($player);
    }
}
