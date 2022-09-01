<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\GetPaths;

use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\PathGateway;
use IncentiveFactory\Game\Shared\Query\QueryHandler;

final class GetPaths implements QueryHandler
{
    public function __construct(private PathGateway $pathGateway)
    {
    }

    /**
     * @return array<array-key, Path>
     */
    public function __invoke(ListOfPaths $listOfPaths): array
    {
        return $this->pathGateway->findAll();
    }
}
