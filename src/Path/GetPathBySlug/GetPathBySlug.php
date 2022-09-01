<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\GetPathBySlug;

use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\PathGateway;
use IncentiveFactory\Game\Shared\Query\QueryHandler;

final class GetPathBySlug implements QueryHandler
{
    public function __construct(private PathGateway $pathGateway)
    {
    }

    public function __invoke(PathSlug $pathSlug): ?Path
    {
        return $this->pathGateway->findOneBySlug($pathSlug->slug);
    }
}
