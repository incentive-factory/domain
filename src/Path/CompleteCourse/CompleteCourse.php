<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\CompleteCourse;

use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;
use IncentiveFactory\Domain\Shared\EventDispatcher\EventDispatcher;

final class CompleteCourse implements CommandHandler
{
    public function __construct(
        private CourseLogGateway $courseLogGateway,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function __invoke(CompletingOfCourse $completingOfCourse): void
    {
        if ($completingOfCourse->courseLog->hasCompleted()) {
            throw new CourseAlreadyCompletedException('Course already completed');
        }

        $completingOfCourse->courseLog->complete();

        $this->courseLogGateway->complete($completingOfCourse->courseLog);

        $this->eventDispatcher->dispatch(new CourseCompleted($completingOfCourse->courseLog));
    }
}
