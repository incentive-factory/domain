<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Path;

use DateTimeImmutable;
use IncentiveFactory\Game\Path\BeginTraining\BeginningOfTraining;
use IncentiveFactory\Game\Path\BeginTraining\TrainingAlreadyBeganException;
use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\PathGateway;
use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Path\TrainingGateway;
use IncentiveFactory\Game\Tests\Application\Repository\InMemoryPathRepository;
use IncentiveFactory\Game\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Game\Tests\CommandTestCase;

final class BeginTrainingTest extends CommandTestCase
{
    public function testShouldBeginTraining(): void
    {
        /** @var TrainingGateway $trainingGateway */
        $trainingGateway = $this->container->get(TrainingGateway::class);

        /** @var Training $training */
        $training = $trainingGateway->findOneBySlug('training-1');

        $player = InMemoryPlayerRepository::createPlayer(1, '01GBJK7XV3YXQ51EHN9G5DAMYN');

        $this->commandBus->execute(new BeginningOfTraining($player, $training));

        /** @var InMemoryPathRepository $pathGateway */
        $pathGateway = $this->container->get(PathGateway::class);

        self::assertTrue($pathGateway->hasAlreadyBegan($player, $training));

        /** @var array<array-key, Path> $paths */
        $paths = array_values(
            array_filter(
                $pathGateway->paths,
                static fn (Path $path) => $path->player()->id()->equals($player->id()) && $path->training()->id()->equals($training->id()),
            )
        );

        self::assertCount(1, $paths);

        $path = $paths[0];

        self::assertEquals($player, $path->player());
        self::assertEquals($training, $path->training());
        self::assertLessThan(new DateTimeImmutable(), $path->beganAt());
    }

    public function testShouldRaiseAnExceptionDueToAPathAlreadyBegan(): void
    {
        /** @var TrainingGateway $trainingGateway */
        $trainingGateway = $this->container->get(TrainingGateway::class);

        /** @var Training $training */
        $training = $trainingGateway->findOneBySlug('training-1');

        $player = InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY');

        self::expectException(TrainingAlreadyBeganException::class);

        $this->commandBus->execute(new BeginningOfTraining($player, $training));
    }
}
