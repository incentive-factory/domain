<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\GetCoursesByTraining\TrainingCourses;
use IncentiveFactory\Domain\Path\TrainingGateway;
use IncentiveFactory\Domain\Tests\QueryTestCase;

final class GetCoursesByTrainingTest extends QueryTestCase
{
    public function testShouldReturnListOfTrainings(): void
    {
        $training = $this->container->get(TrainingGateway::class)->getTrainingBySlug('training-1');

        /** @var array<array-key, Course> $course */
        $course = $this->queryBus->fetch(new TrainingCourses($training));

        self::assertCount(2, $course);
        self::assertContainsOnlyInstancesOf(Course::class, $course);
    }
}
