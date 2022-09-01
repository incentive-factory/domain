<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Path;

use IncentiveFactory\Game\Path\GetTranings\ListOfTrainings;
use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Tests\QueryTestCase;

final class GetTrainingsTest extends QueryTestCase
{
    public function testShouldReturnListOfPath(): void
    {
        /** @var array<array-key, Training> $trainings */
        $trainings = $this->queryBus->fetch(new ListOfTrainings());

        self::assertCount(3, $trainings);
        self::assertContainsOnlyInstancesOf(Training::class, $trainings);
    }
}
