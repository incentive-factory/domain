<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests;

use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Shared\Command\CommandBus;
use IncentiveFactory\Game\Shared\Event\EventBus;
use IncentiveFactory\Game\Tests\Application\Container\Container;
use IncentiveFactory\Game\Tests\Application\CQRS\TestEventBus;
use IncentiveFactory\Game\Tests\Application\Repository\InMemoryPlayerRepository;

abstract class CommandTestCase extends ContainerTestCase
{
    protected CommandBus $commandBus;

    protected TestEventBus $eventBus;

    protected Container $container;

    public function setUp(): void
    {
        $this->container = self::createContainer();
        $this->commandBus = $this->container->get(CommandBus::class);
        $this->eventBus = $this->container->get(EventBus::class);
    }

    protected function tearDown(): void
    {
        /** @var InMemoryPlayerRepository $playerRepository */
        $playerRepository = $this->container->get(PlayerGateway::class);
        $playerRepository->init();

        $this->eventBus->reset();
    }
}
