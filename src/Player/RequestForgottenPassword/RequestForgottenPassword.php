<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\RequestForgottenPassword;

use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;
use IncentiveFactory\Domain\Shared\EventDispatcher\EventDispatcher;
use IncentiveFactory\Domain\Shared\Uid\UuidGeneratorInterface;

final class RequestForgottenPassword implements CommandHandler
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private PlayerGateway $playerGateway,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function __invoke(ForgottenPasswordRequest $forgottenPasswordRequest): void
    {
        $player = $this->playerGateway->getPlayerByEmail($forgottenPasswordRequest->email);

        if (null === $player) {
            return;
        }

        $player->forgotPassword($this->uuidGenerator->generate());
        $this->playerGateway->update($player);
        $this->eventDispatcher->dispatch(new ForgottenPasswordRequested($player));
    }
}
