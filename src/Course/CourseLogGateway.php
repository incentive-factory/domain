<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course;

use IncentiveFactory\Domain\Shared\Entity\PathInterface;
use IncentiveFactory\Domain\Shared\Gateway;

/**
 * @template-extends Gateway<CourseLog>
 */
interface CourseLogGateway extends Gateway
{
    public function begin(CourseLog $courseLog): void;

    public function complete(CourseLog $courseLog): void;

    public function hasAlreadyBegan(PathInterface $path, Course $course): bool;
}
