<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Fixtures;

use IncentiveFactory\Game\Player\CreateRegistrationToken\CreateRegistrationToken;
use IncentiveFactory\Game\Player\Register\NewRegistration;
use IncentiveFactory\Game\Shared\Event\Event;
use IncentiveFactory\Game\Shared\Event\EventBus;
use IncentiveFactory\Game\Shared\Event\EventListener;
use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;

final class TestEventBus implements EventBus
{
    /**
     * @var array<class-string<Event>, EventListener>
     */
    private array $eventListeners = [];

    public function __construct()
    {
        global $container;

        $playerGateway = $container->get(InMemoryPlayerRepository::class);

        $this->eventListeners[NewRegistration::class] = new CreateRegistrationToken(
            new class() implements UuidGeneratorInterface {
                public function generate(): Uuid
                {
                    return Uuid::fromString('d8868bcb-31f5-4e95-96ba-a9b6b7a23157');
                }
            },
            $playerGateway
        );
    }

    public function dispatch(Event $event): void
    {
        if (!isset($this->eventListeners[$event::class])) {
            return;
        }

        $eventListener = $this->eventListeners[$event::class];
        $eventListener->__invoke($event);
    }
}
