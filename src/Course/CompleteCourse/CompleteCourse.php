<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course\CompleteCourse;

use IncentiveFactory\Domain\Course\CourseLogGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;
use IncentiveFactory\Domain\Shared\Event\EventBus;

final class CompleteCourse implements CommandHandler
{
    public function __construct(
        private CourseLogGateway $courseLogGateway,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(CompletingOfCourse $completingOfCourse): void
    {
        if ($completingOfCourse->courseLog->hasCompleted()) {
            throw new CourseAlreadyCompletedException('Course already completed');
        }

        $completingOfCourse->courseLog->complete();

        $this->courseLogGateway->complete($completingOfCourse->courseLog);

        $this->eventBus->dispatch(new CourseCompleted($completingOfCourse->courseLog));
    }
}
