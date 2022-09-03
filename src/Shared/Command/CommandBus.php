<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Shared\Command;

interface CommandBus
{
    public function execute(Command $command): void;
}
