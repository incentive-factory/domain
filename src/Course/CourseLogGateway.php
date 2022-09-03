<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course;

use IncentiveFactory\Game\Shared\Entity\PlayerInterface;
use IncentiveFactory\Game\Shared\Gateway;

/**
 * @template-extends Gateway<CourseLog>
 */
interface CourseLogGateway extends Gateway
{
    public function begin(CourseLog $courseLog): void;

    public function complete(CourseLog $courseLog): void;

    public function hasAlreadyBegan(PlayerInterface $player, Course $course): bool;
}
