<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Course;

use DateTimeImmutable;
use DateTimeInterface;
use IncentiveFactory\Domain\Shared\Entity\PathInterface;
use Symfony\Component\Uid\Ulid;

final class CourseLog
{
    private Ulid $id;

    private PathInterface $path;

    private Course $course;

    private DateTimeInterface $beganAt;

    private ?DateTimeInterface $completedAt = null;

    public static function create(Ulid $id, PathInterface $path, Course $course, DateTimeInterface $beganAt, ?DateTimeInterface $completedAt = null): self
    {
        $registration = new self();
        $registration->id = $id;
        $registration->path = $path;
        $registration->course = $course;
        $registration->beganAt = $beganAt;
        $registration->completedAt = $completedAt;

        return $registration;
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function path(): PathInterface
    {
        return $this->path;
    }

    public function course(): Course
    {
        return $this->course;
    }

    public function beganAt(): DateTimeInterface
    {
        return $this->beganAt;
    }

    public function completedAt(): ?DateTimeInterface
    {
        return $this->completedAt;
    }

    public function hasCompleted(): bool
    {
        return null !== $this->completedAt;
    }

    public function complete(): void
    {
        $this->completedAt = new DateTimeImmutable();
    }
}
