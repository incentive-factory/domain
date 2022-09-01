<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Path;

use IncentiveFactory\Game\Path\BeginPath\BeginningOfPath;
use IncentiveFactory\Game\Path\BeginPath\PathAlreadyBeganException;
use IncentiveFactory\Game\Path\PathGateway;
use IncentiveFactory\Game\Path\Player;
use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Path\TrainingGateway;
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

        /** @var PathGateway $pathGateway */
        $pathGateway = $this->container->get(PathGateway::class);

        self::assertTrue($pathGateway->hasAlreadyBegan($player, $training));
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
