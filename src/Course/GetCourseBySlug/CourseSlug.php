<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course\GetCourseBySlug;

use IncentiveFactory\Game\Shared\Query\Query;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CourseSlug implements Query
{
    public function __construct(#[NotBlank] public string $slug)
    {
    }
}
