<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken;

use IncentiveFactory\Game\Shared\Query\Query;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

final class ForgottenPasswordToken implements Query
{
    public function __construct(
        #[NotBlank]
        #[Uuid]
        public string $token
    ) {
    }
}
