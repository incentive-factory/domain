<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path\GetPathBySlug;

use IncentiveFactory\Game\Shared\Query\Query;
use Symfony\Component\Validator\Constraints\NotBlank;

final class PathSlug implements Query
{
    public function __construct(#[NotBlank] public string $slug)
    {
    }
}
