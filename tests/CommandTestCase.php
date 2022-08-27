<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests;

use IncentiveFactory\Game\Shared\Command\Command;
use IncentiveFactory\Game\Shared\Command\CommandBus;
use IncentiveFactory\Game\Shared\Command\CommandHandler;
use IncentiveFactory\Game\Shared\Event\EventBus;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

abstract class CommandTestCase extends TestCase
{
    private CommandBus $commandBus;

    protected EventBus $eventBus;

    protected function getContainer(): ContainerInterface
    {
        global $container;

        return $container;
    }

    protected function setUp(): void
    {
        $this->eventBus = new TestEventBus();

        $this->commandBus = new TestCommandBus();

        foreach ($this->registerHandlers() as $handler) {
            $this->commandBus->register($handler);
        }
    }

    /**
     * @dataProvider provideCommands
     */
    public function testCommand(Command $command, callable $callback): void
    {
        $this->commandBus->execute($command);

        $callback($this);
    }

    /**
     * @dataProvider provideInvalidCommands
     */
    public function testInvalidCommand(Command $command): void
    {
        self::expectException(ValidationFailedException::class);

        $this->commandBus->execute($command);
    }

    /**
     * @return iterable<array-key, CommandHandler>
     */
    abstract protected function registerHandlers(): iterable;

    /**
     * @return iterable<string, array{command: Command, callback: callable}>
     */
    abstract public function provideCommands(): iterable;

    /**
     * @return iterable<string, array{command: Command}>
     */
    public function provideInvalidCommands(): iterable
    {
        return [];
    }
}
