<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\Register;

use IncentiveFactory\Game\Shared\Command\CommandHandler;

interface RegisterInterface extends CommandHandler
{
    public function __invoke(Registration $registration): void;
}
