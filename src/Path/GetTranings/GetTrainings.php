<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetTranings;

use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Path\TrainingGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

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
