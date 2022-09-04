<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path;

use IncentiveFactory\Domain\Shared\Gateway;

/**
 * @template-extends Gateway<Course>
 */
interface CourseGateway extends Gateway
{
    public function getCourseBySlug(string $slug): ?Course;

    public function countCoursesByTraining(Training $training): int;
}
