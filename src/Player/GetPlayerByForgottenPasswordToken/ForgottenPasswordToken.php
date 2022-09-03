<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\GetPlayerByForgottenPasswordToken;

use IncentiveFactory\Domain\Shared\Query\Query;
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
