<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\Register;

use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;
use IncentiveFactory\Domain\Shared\Event\EventBus;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class Register implements CommandHandler
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private UlidGeneratorInterface $ulidGenerator,
        private PlayerGateway $playerGateway,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(Registration $registration): void
    {
        $player = Player::create(
            id: $this->ulidGenerator->generate(),
            email: $registration->email,
            gender: Gender::from($registration->gender),
            nickname: $registration->nickname,
            password: $this->passwordHasher->hash($registration->plainPassword)
        );

        $this->playerGateway->register($player);

        $this->eventBus->dispatch(new NewRegistration($player));
    }
}
