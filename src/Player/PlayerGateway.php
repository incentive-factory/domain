<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player;

interface PlayerGateway
{
    public function register(Player $player): void;

    public function hasEmail(string $email): bool;

    public function hasRegistrationToken(string $registrationToken): bool;

    public function findOneByRegistrationToken(string $registrationToken): ?Player;

    public function update(Player $player): void;
}
