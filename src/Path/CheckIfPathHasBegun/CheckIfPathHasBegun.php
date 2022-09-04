<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\CheckIfPathHasBegun;

use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

final class CheckIfPathHasBegun implements QueryHandler
{
    public function __construct(private PathGateway $pathGateway)
    {
    }

    public function __invoke(PathBegan $query): bool
    {
        return $this->pathGateway->hasAlreadyBegun($query->player, $query->training);
    }
}
