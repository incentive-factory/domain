<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use DateTimeImmutable;
use IncentiveFactory\Domain\Path\BeginTraining\BeginningOfTraining;
use IncentiveFactory\Domain\Path\BeginTraining\TrainingAlreadyBeganException;
use IncentiveFactory\Domain\Path\BeginTraining\TrainingBegan;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Path\TrainingGateway;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPathRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Domain\Tests\CommandTestCase;

final class BeginTrainingTest extends CommandTestCase
{
    public function testShouldBeginTraining(): void
    {
        /** @var TrainingGateway $trainingGateway */
        $trainingGateway = $this->container->get(TrainingGateway::class);

        /** @var Training $training */
        $training = $trainingGateway->getTrainingBySlug('training-1');

        $player = InMemoryPlayerRepository::createPlayer(1, '01GBJK7XV3YXQ51EHN9G5DAMYN');

        $this->commandBus->execute(new BeginningOfTraining($player, $training));

        /** @var InMemoryPathRepository $pathGateway */
        $pathGateway = $this->container->get(PathGateway::class);

        self::assertTrue($pathGateway->hasAlreadyBegun($player, $training));

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
        self::assertTrue($this->eventDispatcher->hasDispatched(TrainingBegan::class));
    }

    public function testShouldRaiseAnExceptionDueToAPathAlreadyBegan(): void
    {
        /** @var TrainingGateway $trainingGateway */
        $trainingGateway = $this->container->get(TrainingGateway::class);

        /** @var Training $training */
        $training = $trainingGateway->getTrainingBySlug('training-1');

        $player = InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY');

        self::expectException(TrainingAlreadyBeganException::class);

        $this->commandBus->execute(new BeginningOfTraining($player, $training));
    }
}
