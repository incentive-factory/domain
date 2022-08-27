<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Shared\Command;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

final class TestCommandBus implements CommandBus
{
    /**
     * @var array<class-string<Command>, CommandHandler>
     */
    private array $handlers = [];

    /**
     * @throws ReflectionException
     */
    public function register(CommandHandler $handler): void
    {
        $reflectionMethod = new ReflectionMethod($handler, '__invoke');

        if (1 !== $reflectionMethod->getNumberOfParameters()) {
            throw new InvalidArgumentException('Command handler must have one parameter');
        }

        /** @var ReflectionNamedType $commandType */
        $commandType = $reflectionMethod->getParameters()[0]->getType();

        /** @var class-string<Command> $commandClass */
        $commandClass = $commandType->getName();

        $reflectionClass = new ReflectionClass($commandClass);

        if (false === $reflectionClass->implementsInterface(Command::class)) {
            throw new InvalidArgumentException('Command handler must have parameter of type Command');
        }

        $this->handlers[$commandClass] = $handler;
    }

    public function execute(Command $command): void
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validate($command);

        if (count($violations) > 0) {
            throw new ValidationFailedException($command, $violations);
        }

        $this->handlers[$command::class]->__invoke($command);
    }
}
