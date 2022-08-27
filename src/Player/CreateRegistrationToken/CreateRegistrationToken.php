<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\CreateRegistrationToken;

use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Player\Register\NewRegistration;
use IncentiveFactory\Game\Shared\Event\EventListener;
use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;

final class CreateRegistrationToken implements EventListener
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private PlayerGateway $playerGateway
    ) {
    }

    public function __invoke(NewRegistration $newRegistration): void
    {
        $player = $newRegistration->player;

        $player->prepareValidationOfRegistration($this->uuidGenerator->generate());

        $this->playerGateway->update($player);
    }
}
