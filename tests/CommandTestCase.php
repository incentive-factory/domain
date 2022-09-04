<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests;

use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Shared\Command\CommandBus;
use IncentiveFactory\Domain\Shared\EventDispatcher\EventDispatcher;
use IncentiveFactory\Domain\Tests\Application\Container\Container;
use IncentiveFactory\Domain\Tests\Application\EventDispatcher\TestEventDispatcher;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPlayerRepository;

abstract class CommandTestCase extends ContainerTestCase
{
    protected CommandBus $commandBus;

    protected TestEventDispatcher $eventBus;

    protected Container $container;

    public function setUp(): void
    {
        $this->container = self::createContainer();
        $this->commandBus = $this->container->get(CommandBus::class);
        $this->eventBus = $this->container->get(EventDispatcher::class);
    }

    protected function tearDown(): void
    {
        /** @var InMemoryPlayerRepository $playerRepository */
        $playerRepository = $this->container->get(PlayerGateway::class);
        $playerRepository->init();

        $this->eventBus->reset();
    }
}
