<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Shared\Event;

interface EventBus
{
    public function dispatch(Event $event): void;
}
