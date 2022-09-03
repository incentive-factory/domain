<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\GetPathsByPlayer;

use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\PathGateway;
use IncentiveFactory\Game\Shared\Query\QueryHandler;

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
