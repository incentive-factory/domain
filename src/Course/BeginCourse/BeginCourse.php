<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course\BeginCourse;

use DateTimeImmutable;
use IncentiveFactory\Game\Course\CourseLog;
use IncentiveFactory\Game\Course\CourseLogGateway;
use IncentiveFactory\Game\Shared\Command\CommandHandler;
use IncentiveFactory\Game\Shared\Uid\UlidGeneratorInterface;

final class BeginCourse implements CommandHandler
{
    public function __construct(private CourseLogGateway $courseLogGateway, private UlidGeneratorInterface $ulidGenerator)
    {
    }

    public function __invoke(BeginningOfCourse $beginningOfCourse): void
    {
        if ($this->courseLogGateway->hasAlreadyBegan($beginningOfCourse->player, $beginningOfCourse->course)) {
            throw new CourseAlreadyBeganException('Course already began');
        }

        $course = CourseLog::create(
            $this->ulidGenerator->generate(),
            $beginningOfCourse->player,
            $beginningOfCourse->course,
            new DateTimeImmutable()
        );

        $this->courseLogGateway->begin($course);
    }
}
