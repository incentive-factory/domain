<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetPathById;

use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

final class GetPathById implements QueryHandler
{
    public function __construct(private PathGateway $pathGateway)
    {
    }

    public function __invoke(PathId $query): ?Path
    {
        return $this->pathGateway->getPathById($query->id);
    }
}
