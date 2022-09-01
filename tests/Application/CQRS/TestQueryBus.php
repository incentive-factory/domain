<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\CQRS;

use IncentiveFactory\Game\Shared\Query\Query;
use IncentiveFactory\Game\Shared\Query\QueryBus;
use IncentiveFactory\Game\Shared\Query\QueryHandler;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;

final class TestQueryBus implements QueryBus
{
    /**
     * @param array<class-string<Query>, class-string<QueryHandler>> $handlers
     */
    public function __construct(private ContainerInterface $container, private array $handlers)
    {
    }

    public function fetch(Query $query): mixed
    {
        $constraintValidatorFactory = new ContainerConstraintValidatorFactory($this->container);

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory($constraintValidatorFactory)
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validate($query);

        if (count($violations) > 0) {
            throw new ValidationFailedException($query, $violations);
        }

        return $this->container->get($this->handlers[$query::class])->__invoke($query);
    }
}
