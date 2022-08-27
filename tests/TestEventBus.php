<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests;

use IncentiveFactory\Game\Player\CreateRegistrationToken\CreateRegistrationToken;
use IncentiveFactory\Game\Player\Register\NewRegistration;
use IncentiveFactory\Game\Shared\Event\Event;
use IncentiveFactory\Game\Shared\Event\EventBus;
use IncentiveFactory\Game\Shared\Event\EventListener;
use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;
use IncentiveFactory\Game\Tests\Player\InMemoryPlayerRepository;
use Symfony\Component\Uid\Uuid;

final class TestEventBus implements EventBus
{
    /**
     * @var array<class-string<Event>, EventListener>
     */
    private array $eventListeners = [];

    public function __construct()
    {
        $this->eventListeners[NewRegistration::class] = new CreateRegistrationToken(
            new class() implements UuidGeneratorInterface {
                public function generate(): Uuid
                {
                    return Uuid::fromString('d8868bcb-31f5-4e95-96ba-a9b6b7a23157');
                }
            },
            new InMemoryPlayerRepository()
        );
    }

    public function dispatch(Event $event): void
    {
        $eventListener = $this->eventListeners[$event::class];
        $eventListener->__invoke($event);
    }
}
