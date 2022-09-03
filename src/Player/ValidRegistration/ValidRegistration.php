<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\ValidRegistration;

use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;

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
