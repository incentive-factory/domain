<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path;

use DateTimeInterface;
use IncentiveFactory\Domain\Shared\Entity\TrainingInterface;
use Symfony\Component\Uid\Ulid;

final class Training implements TrainingInterface
{
    private Ulid $id;

    private DateTimeInterface $publishedAt;

    private string $name;

    private string $slug;

    private string $description;

    private Level $level;

    private string $prerequisites;

    private string $skills;

    private string $image;

    public static function create(
        Ulid $id,
        DateTimeInterface $publishedAt,
        string $slug,
        string $name,
        string $description,
        Level $level,
        string $prerequisites,
        string $skills,
        string $image
    ): self {
        $training = new self();
        $training->id = $id;
        $training->publishedAt = $publishedAt;
        $training->slug = $slug;
        $training->name = $name;
        $training->description = $description;
        $training->level = $level;
        $training->prerequisites = $prerequisites;
        $training->skills = $skills;
        $training->image = $image;

        return $training;
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function publishedAt(): DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function level(): Level
    {
        return $this->level;
    }

    public function prerequisites(): string
    {
        return $this->prerequisites;
    }

    public function skills(): string
    {
        return $this->skills;
    }

    public function image(): string
    {
        return $this->image;
    }
}
