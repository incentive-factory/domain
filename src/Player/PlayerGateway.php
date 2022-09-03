<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player;

use IncentiveFactory\Domain\Shared\Gateway;

/**
 * @template-extends Gateway<Player>
 */
interface PlayerGateway extends Gateway
{
    public function register(Player $player): void;

    public function hasEmail(string $email, ?Player $player = null): bool;

    public function hasRegistrationToken(string $registrationToken): bool;

    public function findOneByEmail(string $email): ?Player;

    public function findOneByRegistrationToken(string $registrationToken): ?Player;

    public function findOneByForgottenPasswordToken(string $forgottenPasswordToken): ?Player;

    public function update(Player $player): void;
}
