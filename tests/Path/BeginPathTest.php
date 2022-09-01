<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Path;

use DateTimeImmutable;
use IncentiveFactory\Game\Path\BeginPath\BeginningOfPath;
use IncentiveFactory\Game\Path\BeginPath\PathAlreadyBeganException;
use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\PathGateway;
use IncentiveFactory\Game\Path\Player;
use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Path\TrainingGateway;
use IncentiveFactory\Game\Tests\Application\Repository\InMemoryPathRepository;
use IncentiveFactory\Game\Tests\CommandTestCase;
use Symfony\Component\Uid\Ulid;

final class BeginPathTest extends CommandTestCase
{
    public function testShouldBeginPath(): void
    {
        /** @var TrainingGateway $trainingGateway */
        $trainingGateway = $this->container->get(TrainingGateway::class);

        /** @var Training $training */
        $training = $trainingGateway->findOneBySlug('training-1');

        $player = Player::create(Ulid::fromString('01GBJK7XV3YXQ51EHN9G5DAMYN'));

        $this->commandBus->execute(new BeginningOfPath($player, $training));

        /** @var InMemoryPathRepository $pathGateway */
        $pathGateway = $this->container->get(PathGateway::class);

        self::assertTrue($pathGateway->hasAlreadyBegan($player, $training));

        /** @var array<array-key, Path> $paths */
        $paths = array_values(
            array_filter(
                $pathGateway->paths,
                static fn (Path $path) => $path->player()->id()->equals($player->id()) && $path->path()->id()->equals($training->id()),
            )
        );

        self::assertCount(1, $paths);

        $path = $paths[0];

        self::assertEquals($player, $path->player());
        self::assertEquals($training, $path->path());
        self::assertLessThan(new DateTimeImmutable(), $path->beganAt());
    }

    public function testShouldRaiseAnExceptionDueToAPathAlreadyBegan(): void
    {
        /** @var TrainingGateway $trainingGateway */
        $trainingGateway = $this->container->get(TrainingGateway::class);

        /** @var Training $training */
        $training = $trainingGateway->findOneBySlug('training-1');

        $player = Player::create(Ulid::fromString('01GBJK7XV3YXQ51EHN9G5DAMYN'));

        $this->commandBus->execute(new BeginningOfPath($player, $training));

        self::expectException(PathAlreadyBeganException::class);

        $this->commandBus->execute(new BeginningOfPath($player, $training));
    }
}
