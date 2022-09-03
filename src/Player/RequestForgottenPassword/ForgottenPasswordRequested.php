<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\RequestForgottenPassword;

use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Shared\Event\Event;

final class ForgottenPasswordRequested implements Event
{
    public function __construct(public Player $player)
    {
    }
}
