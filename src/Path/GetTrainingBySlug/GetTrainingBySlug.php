<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetTrainingBySlug;

use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Path\TrainingGateway;
use IncentiveFactory\Domain\Shared\Query\QueryHandler;

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
