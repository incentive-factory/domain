<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Shared\EventDispatcher;

interface EventDispatcher
{
    public function dispatch(Event $event): void;
}
