<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken;

use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Shared\Query\QueryHandler;

final class GetPlayerByForgottenPasswordToken implements QueryHandler
{
    public function __construct(private PlayerGateway $playerGateway)
    {
    }

    public function __invoke(ForgottenPasswordToken $query): ?Player
    {
        $player = $this->playerGateway->findOneByForgottenPasswordToken($query->token);

        if (null === $player) {
            return null;
        }

        if ($player->hasForgottenPasswordTokenExpired()) {
            throw new ForgottenPasswordTokenExpiredException();
        }

        return $player;
    }
}
