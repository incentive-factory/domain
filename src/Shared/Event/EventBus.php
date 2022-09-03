<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Shared\Event;

interface EventBus
{
    public function dispatch(Event $event): void;
}
