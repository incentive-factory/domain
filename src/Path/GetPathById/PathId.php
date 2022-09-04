<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetPathById;

use IncentiveFactory\Domain\Shared\Query\Query;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid;

final class PathId implements Query
{
    public function __construct(
        #[Ulid]
        #[NotBlank]
        public string $id
    ) {
    }
}
