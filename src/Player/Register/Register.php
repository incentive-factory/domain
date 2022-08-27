<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\Register;

use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Shared\Uid\UlidGeneratorInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class Register implements RegisterInterface
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private UlidGeneratorInterface $ulidGenerator,
        private PlayerGateway $playerGateway
    ) {
    }

    public function __invoke(Registration $registration): void
    {
        $player = Player::create(
            $this->ulidGenerator->generate(),
            $registration->email,
            $registration->nickname,
            $this->passwordHasher->hash($registration->plainPassword)
        );

        $this->playerGateway->register($player);
    }
}
