<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Shared\Command;

interface CommandBus
{
    public function execute(Command $command): void;
}
