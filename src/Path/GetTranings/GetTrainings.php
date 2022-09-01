<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\GetTranings;

use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Path\TrainingGateway;
use IncentiveFactory\Game\Shared\Query\QueryHandler;

final class GetTrainings implements QueryHandler
{
    public function __construct(private TrainingGateway $trainingGateway)
    {
    }

    /**
     * @return array<array-key, Training>
     */
    public function __invoke(ListOfTrainings $listOfPaths): array
    {
        return $this->trainingGateway->findAll();
    }
}
