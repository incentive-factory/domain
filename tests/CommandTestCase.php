<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests;

use IncentiveFactory\Game\Shared\Command\Command;
use IncentiveFactory\Game\Shared\Command\CommandBus;
use IncentiveFactory\Game\Shared\Command\CommandHandler;
use IncentiveFactory\Game\Shared\Command\TestCommandBus;
use PHPUnit\Framework\TestCase;

abstract class CommandTestCase extends TestCase
{
    private CommandBus $commandBus;

    protected function setUp(): void
    {
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
     * @return iterable<array-key, CommandHandler>
     */
    abstract protected function registerHandlers(): iterable;

    /**
     * @return iterable<string, array{command: Command, callback: callable}>
     */
    abstract public function provideCommands(): iterable;
}
