<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path;

use DateTimeInterface;
use Symfony\Component\Uid\Ulid;

final class Path
{
    private Ulid $id;

    private DateTimeInterface $publishedAt;

    private string $name;

    private string $description;

    private Level $level;

    private string $prerequisites;

    private string $skills;

    private string $image;

    public static function create(
        Ulid $id,
        DateTimeInterface $publishedAt,
        string $name,
        string $description,
        Level $level,
        string $prerequisites,
        string $skills,
        string $image
    ): self {
        $path = new self();
        $path->id = $id;
        $path->publishedAt = $publishedAt;
        $path->name = $name;
        $path->description = $description;
        $path->level = $level;
        $path->prerequisites = $prerequisites;
        $path->skills = $skills;
        $path->image = $image;

        return $path;
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function publishedAt(): DateTimeInterface
    {
        return $this->publishedAt;
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
