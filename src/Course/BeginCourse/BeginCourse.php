<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course\BeginCourse;

use DateTimeImmutable;
use IncentiveFactory\Domain\Course\CourseLog;
use IncentiveFactory\Domain\Course\CourseLogGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;
use IncentiveFactory\Domain\Shared\Event\EventBus;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;

final class BeginCourse implements CommandHandler
{
    public function __construct(
        private CourseLogGateway $courseLogGateway,
        private UlidGeneratorInterface $ulidGenerator,
        private EventBus $eventBus
    ) {
    }

    public function __invoke(BeginningOfCourse $beginningOfCourse): void
    {
        if ($this->courseLogGateway->hasAlreadyBegan($beginningOfCourse->player, $beginningOfCourse->course)) {
            throw new CourseAlreadyBeganException('Course already began');
        }

        $courseLog = CourseLog::create(
            $this->ulidGenerator->generate(),
            $beginningOfCourse->player,
            $beginningOfCourse->course,
            new DateTimeImmutable()
        );

        $this->courseLogGateway->begin($courseLog);

        $this->eventBus->dispatch(new CourseBegan($courseLog));
    }
}
