<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course;

use IncentiveFactory\Game\Shared\Gateway;

/**
 * @template-extends Gateway<Course>
 */
interface CourseGateway extends Gateway
{
    public function findOneBySlug(string $slug): ?Course;
}
