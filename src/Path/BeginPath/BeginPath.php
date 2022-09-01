<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\BeginPath;

use DateTimeImmutable;
use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\PathGateway;
use IncentiveFactory\Game\Shared\Command\CommandHandler;
use IncentiveFactory\Game\Shared\Uid\UlidGeneratorInterface;

final class BeginPath implements CommandHandler
{
    public function __construct(private PathGateway $pathGateway, private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function __invoke(BeginningOfPath $beginningOfPath): void
    {
        if ($this->pathGateway->hasAlreadyBegan($beginningOfPath->player, $beginningOfPath->training)) {
            throw new PathAlreadyBeganException('Path already began');
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
