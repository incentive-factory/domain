<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\GetPlayerByForgottenPasswordToken;

use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

final class GetPlayerByForgottenPasswordToken implements QueryHandler
{
    public function __construct(private PlayerGateway $playerGateway)
    {
    }

    public function __invoke(ForgottenPasswordToken $query): ?Player
    {
        $player = $this->playerGateway->getPlayerByForgottenPasswordToken($query->token);

        if (null === $player) {
            return null;
        }

        if ($player->hasForgottenPasswordTokenExpired()) {
            throw new ForgottenPasswordTokenExpiredException();
        }

        return $player;
    }
}
