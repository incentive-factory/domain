<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course;

use DateTimeInterface;
use IncentiveFactory\Game\Shared\Entity\PlayerInterface;
use Symfony\Component\Uid\Ulid;

final class CourseLog
{
    private Ulid $id;

    private PlayerInterface $player;

    private Course $course;

    private DateTimeInterface $beganAt;

    public static function create(Ulid $id, PlayerInterface $player, Course $course, DateTimeInterface $beganAt): self
    {
        $registration = new self();
        $registration->id = $id;
        $registration->player = $player;
        $registration->course = $course;
        $registration->beganAt = $beganAt;

        return $registration;
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function player(): PlayerInterface
    {
        return $this->player;
    }

    public function course(): Course
    {
        return $this->course;
    }

    public function beganAt(): DateTimeInterface
    {
        return $this->beganAt;
    }
}
