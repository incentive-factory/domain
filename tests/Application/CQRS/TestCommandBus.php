<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\CQRS;

use IncentiveFactory\Game\Shared\Command\Command;
use IncentiveFactory\Game\Shared\Command\CommandBus;
use IncentiveFactory\Game\Shared\Command\CommandHandler;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;

final class TestCommandBus implements CommandBus
{
    /**
     * @param array<class-string<Command>, class-string<CommandHandler>> $handlers
     */
    public function __construct(private ContainerInterface $container, private array $handlers)
    {
    }

    public function execute(Command $command): void
    {
        $constraintValidatorFactory = new ContainerConstraintValidatorFactory($this->container);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory($constraintValidatorFactory)
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validate($command);

        if (count($violations) > 0) {
            throw new ValidationFailedException($command, $violations);
        }

        $this->container->get($this->handlers[$command::class])->__invoke($command);
    }
}
