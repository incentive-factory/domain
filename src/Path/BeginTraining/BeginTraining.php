<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\BeginTraining;

use DateTimeImmutable;
use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\PathGateway;
use IncentiveFactory\Game\Shared\Command\CommandHandler;
use IncentiveFactory\Game\Shared\Uid\UlidGeneratorInterface;

final class BeginTraining implements CommandHandler
{
    public function __construct(private PathGateway $pathGateway, private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function __invoke(BeginningOfTraining $beginningOfPath): void
    {
        if ($this->pathGateway->hasAlreadyBegan($beginningOfPath->player, $beginningOfPath->training)) {
            throw new TrainingAlreadyBeganException('Path already began');
        }

        $path = Path::create(
            $this->ulidGenerator->generate(),
            $beginningOfPath->player,
            $beginningOfPath->training,
            new DateTimeImmutable()
        );

        $this->pathGateway->begin($path);
    }
}
