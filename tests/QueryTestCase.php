<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests;

use Exception;
use IncentiveFactory\Game\Shared\Query\Query;
use IncentiveFactory\Game\Shared\Query\QueryBus;
use IncentiveFactory\Game\Shared\Query\QueryHandler;
use IncentiveFactory\Game\Tests\Fixtures\TestQueryBus;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

abstract class QueryTestCase extends TestCase
{
    private QueryBus $queryBus;

    protected static function getContainer(): ContainerInterface
    {
        global $container;

        return $container;
    }

    protected function setUp(): void
    {
        $this->queryBus = new TestQueryBus();

        foreach ($this->registerHandlers() as $handler) {
            $this->queryBus->register($handler);
        }
    }

    /**
     * @dataProvider provideQueries
     */
    public function testQuery(Query $query, callable $init, callable $callback): void
    {
        $init($this);
        $callback($this, $this->queryBus->fetch($query));
    }

    /**
     * @dataProvider provideInvalidQueries
     */
    public function testInvalidQuery(Query $query, callable $init): void
    {
        $init($this);

        self::expectException(ValidationFailedException::class);

        $this->queryBus->fetch($query);
    }

    /**
     * @dataProvider provideFailedQueries
     *
     * @param class-string<Exception> $exceptionClass
     */
    public function testFailedQuery(Query $query, callable $init, string $exceptionClass): void
    {
        $init($this);

        self::expectException($exceptionClass);

        $this->queryBus->fetch($query);
    }

    /**
     * @return iterable<array-key, QueryHandler>
     */
    abstract protected function registerHandlers(): iterable;

    /**
     * @return iterable<string, array{query: Query, init: callable, callback: callable}>
     */
    abstract public function provideQueries(): iterable;

    /**
     * @return iterable<string, array{query: Query, init: callable}>
     */
    public function provideInvalidQueries(): iterable
    {
        return [];
    }

    /**
     * @return iterable<string, array{query: Query, init: callable, exceptionClass: class-string<Exception>}>
     */
    public function provideFailedQueries(): iterable
    {
        return [];
    }
}
