<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Fixtures;

use IncentiveFactory\Game\Shared\Query\Query;
use IncentiveFactory\Game\Shared\Query\QueryBus;
use IncentiveFactory\Game\Shared\Query\QueryHandler;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

final class TestQueryBus implements QueryBus
{
    /**
     * @var array<class-string<Query>, QueryHandler>
     */
    private array $handlers = [];

    /**
     * @throws ReflectionException
     */
    public function register(QueryHandler $handler): void
    {
        $reflectionMethod = new ReflectionMethod($handler, '__invoke');

        if (1 !== $reflectionMethod->getNumberOfParameters()) {
            throw new InvalidArgumentException('Event handler must have one parameter');
        }

        /** @var ReflectionNamedType $queryType */
        $queryType = $reflectionMethod->getParameters()[0]->getType();

        /** @var class-string<Query> $queryClass */
        $queryClass = $queryType->getName();

        $reflectionClass = new ReflectionClass($queryClass);

        if (false === $reflectionClass->implementsInterface(Query::class)) {
            throw new InvalidArgumentException('Event handler must have parameter of type Query');
        }

        $this->handlers[$queryClass] = $handler;
    }

    public function fetch(Query $query): mixed
    {
        global $container;

        $constraintValidatorFactory = new ContainerConstraintValidatorFactory($container);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory($constraintValidatorFactory)
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validate($query);

        if (count($violations) > 0) {
            throw new ValidationFailedException($query, $violations);
        }

        return $this->handlers[$query::class]->__invoke($query);
    }
}
