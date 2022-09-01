<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\GetTrainingBySlug;

use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Path\TrainingGateway;
use IncentiveFactory\Game\Shared\Query\QueryHandler;

final class GetTrainingBySlug implements QueryHandler
{
    public function __construct(private TrainingGateway $trainingGateway)
    {
    }

    public function __invoke(TrainingSlug $trainingSlug): ?Training
    {
        return $this->trainingGateway->findOneBySlug($trainingSlug->slug);
    }
}
