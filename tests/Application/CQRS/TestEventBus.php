<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\CQRS;

use IncentiveFactory\Game\Shared\Event\Event;
use IncentiveFactory\Game\Shared\Event\EventBus;
use IncentiveFactory\Game\Shared\Event\EventListener;

final class TestEventBus implements EventBus
{
    /**
     * @var array<array-key, class-string<Event>>
     */
    private array $eventsDispatched = [];

    /**
     * @param array<class-string<Event>, EventListener> $eventListeners
     */
    public function __construct(private array $eventListeners = [])
    {
    }

    public function dispatch(Event $event): void
    {
        $this->eventsDispatched[] = $event::class;

        if (!isset($this->eventListeners[$event::class])) {
            return;
        }

        $eventListener = $this->eventListeners[$event::class];
        $eventListener->__invoke($event);
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
