<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Application\CQRS;

use IncentiveFactory\Domain\Shared\Event\Event;
use IncentiveFactory\Domain\Shared\Event\EventBus;

final class TestEventBus implements EventBus
{
    /**
     * @var array<array-key, class-string<Event>>
     */
    private array $eventsDispatched = [];

    public function __construct()
    {
    }

    public function dispatch(Event $event): void
    {
        $this->eventsDispatched[] = $event::class;
    }

    public function reset(): void
    {
        $this->eventsDispatched = [];
    }

    public function hasDispatched(string $eventClass): bool
    {
        return in_array($eventClass, $this->eventsDispatched, true);
    }
}
