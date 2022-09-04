<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path\BeginCourse;

use DateTimeImmutable;
use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Path\CourseLogGateway;
use IncentiveFactory\Domain\Shared\Command\CommandHandler;
use IncentiveFactory\Domain\Shared\EventDispatcher\EventDispatcher;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;

final class BeginCourse implements CommandHandler
{
    public function __construct(
        private CourseLogGateway $courseLogGateway,
        private UlidGeneratorInterface $ulidGenerator,
        private EventDispatcher $eventDispatcher
    ) {
    }

    public function __invoke(BeginningOfCourse $beginningOfCourse): void
    {
        if ($this->courseLogGateway->hasAlreadyBegan($beginningOfCourse->path->player(), $beginningOfCourse->course)) {
            throw new CourseAlreadyBeganException('Course already began');
        }

        $courseLog = CourseLog::create(
            $this->ulidGenerator->generate(),
            $beginningOfCourse->path,
            $beginningOfCourse->course,
            new DateTimeImmutable()
        );

        $this->courseLogGateway->begin($courseLog);

        $this->eventDispatcher->dispatch(new CourseBegan($courseLog));
    }
}
