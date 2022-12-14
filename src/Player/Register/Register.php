<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\Register;

use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;
use IncentiveFactory\Domain\Shared\EventDispatcher\EventDispatcher;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\Domain\Shared\Uid\UuidGeneratorInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class Register implements CommandHandler
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private UlidGeneratorInterface $ulidGenerator,
        private UuidGeneratorInterface $uuidGenerator,
        private PlayerGateway $playerGateway,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function __invoke(Registration $registration): void
    {
        $player = Player::create(
            id: $this->ulidGenerator->generate(),
            email: $registration->email,
            gender: $registration->gender,
            nickname: $registration->nickname,
            password: $this->passwordHasher->hash($registration->plainPassword)
        );

        $player->prepareValidationOfRegistration($this->uuidGenerator->generate());

        $this->playerGateway->register($player);

        $this->eventDispatcher->dispatch(new NewRegistration($player));
    }
}
