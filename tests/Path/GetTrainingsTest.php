<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use IncentiveFactory\Domain\Path\GetTranings\ListOfTrainings;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Tests\QueryTestCase;

final class GetTrainingsTest extends QueryTestCase
{
    public function testShouldReturnListOfTrainings(): void
    {
        /** @var array<array-key, Training> $trainings */
        $trainings = $this->queryBus->fetch(new ListOfTrainings());

        self::assertCount(3, $trainings);
        self::assertContainsOnlyInstancesOf(Training::class, $trainings);
    }
}
