<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\GetCourseBySlug;

use IncentiveFactory\Domain\Shared\Query\Query;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CourseSlug implements Query
{
    public function __construct(#[NotBlank] public string $slug)
    {
    }
}
