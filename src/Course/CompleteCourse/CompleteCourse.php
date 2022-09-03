<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course\CompleteCourse;

use IncentiveFactory\Game\Course\CourseLogGateway;
use IncentiveFactory\Game\Shared\Command\CommandHandler;
use IncentiveFactory\Game\Shared\Event\EventBus;

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
