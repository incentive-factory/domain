<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\ResetPassword;

use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class ResetPassword implements CommandHandler
{
    public function __construct(
        private PasswordHasherInterface $passwordHasher,
        private PlayerGateway $playerGateway
    ) {
    }

    public function __invoke(NewPassword $newPassword): void
    {
        $player = $newPassword->player;
        $player->newPassword($this->passwordHasher->hash($newPassword->plainPassword));
        $this->playerGateway->update($player);
    }
}
