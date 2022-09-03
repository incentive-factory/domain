<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\GetPlayerByForgottenPasswordToken;

use InvalidArgumentException;

final class ForgottenPasswordTokenExpiredException extends InvalidArgumentException
{
}
