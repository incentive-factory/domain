<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests;

use IncentiveFactory\Game\Shared\Query\QueryBus;
use IncentiveFactory\Game\Tests\Application\Container\Container;

abstract class QueryTestCase extends ContainerTestCase
{
    protected QueryBus $queryBus;

    protected Container $container;

    public function setUp(): void
    {
        $this->container = self::createContainer();
        $this->queryBus = $this->container->get(QueryBus::class);
    }
}
