<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\RequestForgottenPassword;

use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Shared\Event\Event;

final class ForgottenPasswordRequested implements Event
{
    public function __construct(public Player $player)
    {
    }
}
