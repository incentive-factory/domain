<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\ValidRegistration;

use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Shared\Command\CommandHandler;

final class ValidRegistration implements CommandHandler
{
    public function __construct(private PlayerGateway $playerGateway)
    {
    }

    public function __invoke(ValidationOfRegistration $validationOfRegistration): void
    {
        /** @var Player $player */
        $player = $this->playerGateway->findOneByRegistrationToken($validationOfRegistration->registrationToken);
        $player->validateRegistration();
        $this->playerGateway->update($player);
    }
}
