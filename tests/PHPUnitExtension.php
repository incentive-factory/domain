<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests;

use IncentiveFactory\Game\Tests\Fixtures\InMemoryPlayerRepository;
use PHPUnit\Runner\BeforeTestHook;

final class PHPUnitExtension implements BeforeTestHook
{
    public function executeBeforeTest(string $test): void
    {
        global $container;
        /** @var InMemoryPlayerRepository $playerGateway */
        $playerGateway = $container->get(InMemoryPlayerRepository::class);
        $playerGateway->init();
    }
}
