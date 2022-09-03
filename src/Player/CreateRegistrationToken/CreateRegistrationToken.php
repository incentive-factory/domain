<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\CreateRegistrationToken;

use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Player\Register\NewRegistration;
use IncentiveFactory\Domain\Shared\Event\EventListener;
use IncentiveFactory\Domain\Shared\Uid\UuidGeneratorInterface;

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
