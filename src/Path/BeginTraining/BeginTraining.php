<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\BeginTraining;

use DateTimeImmutable;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;
use IncentiveFactory\Domain\Shared\Event\EventBus;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;

final class BeginTraining implements CommandHandler
{
    public function __construct(
        private PathGateway $pathGateway,
        private UlidGeneratorInterface $ulidGenerator,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(BeginningOfTraining $beginningOfPath): void
    {
        if ($this->pathGateway->hasAlreadyBegun($beginningOfPath->player, $beginningOfPath->training)) {
            throw new TrainingAlreadyBeganException('Path already began');
        }

        $path = Path::create(
            $this->ulidGenerator->generate(),
            $beginningOfPath->player,
            $beginningOfPath->training,
            new DateTimeImmutable()
        );

        $this->pathGateway->begin($path);

        $this->eventBus->dispatch(new TrainingBegan($path));
    }
}
