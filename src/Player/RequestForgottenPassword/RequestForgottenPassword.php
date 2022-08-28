<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\RequestForgottenPassword;

use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Shared\Command\CommandHandler;
use IncentiveFactory\Game\Shared\Event\EventBus;
use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;

final class RequestForgottenPassword implements CommandHandler
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private PlayerGateway $playerGateway,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(ForgottenPasswordRequest $forgottenPasswordRequest): void
    {
        $player = $this->playerGateway->findOneByEmail($forgottenPasswordRequest->email);

        if (null === $player) {
            return;
        }

        $player->forgotPassword($this->uuidGenerator->generate());
        $this->playerGateway->update($player);
        $this->eventBus->dispatch(new ForgottenPasswordRequested($player));
    }
}
