<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use IncentiveFactory\Domain\Path\CheckIfPathHasBegun\PathBegan;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Path\TrainingGateway;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Domain\Tests\QueryTestCase;

class CheckIfPathHasBegunTest extends QueryTestCase
{
    public function testShouldReturnTrue(): void
    {
        /** @var TrainingGateway $trainingGateway */
        $trainingGateway = $this->container->get(TrainingGateway::class);

        /** @var Training $training */
        $training = $trainingGateway->getTrainingBySlug('training-1');

        $player = InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY');

        /** @var bool $pathHasBegun */
        $pathHasBegun = $this->queryBus->fetch(new PathBegan($player, $training));

        self::assertTrue($pathHasBegun);
    }
}
