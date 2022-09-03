<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetPathsByPlayer;

use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

final class GetPathsByPlayer implements QueryHandler
{
    public function __construct(private PathGateway $pathGateway)
    {
    }

    /**
     * @return array<array-key, Path>
     */
    public function __invoke(PlayerPaths $playerPaths): array
    {
        return $this->pathGateway->findByPlayer($playerPaths->player);
    }
}
