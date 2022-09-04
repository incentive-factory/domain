<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetCourseLogById;

use IncentiveFactory\Domain\Shared\Query\Query;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ulid;

final class CourseLogId implements Query
{
    public function __construct(
        #[Ulid]
        #[NotBlank]
        public string $id
    ) {
    }
}
