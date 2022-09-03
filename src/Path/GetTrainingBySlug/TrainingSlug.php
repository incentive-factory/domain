<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\GetTrainingBySlug;

use IncentiveFactory\Game\Shared\Query\Query;
use Symfony\Component\Validator\Constraints\NotBlank;

final class TrainingSlug implements Query
{
    public function __construct(#[NotBlank] public string $slug)
    {
    }
}
