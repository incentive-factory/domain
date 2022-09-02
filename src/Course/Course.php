<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Course;

use DateTimeInterface;
use IncentiveFactory\Game\Shared\Entity\TrainingInterface;
use Symfony\Component\Uid\Ulid;

final class Course
{
    private Ulid $id;

    private DateTimeInterface $publishedAt;

    private string $name;

    private string $excerpt;

    private string $content;

    private string $slug;

    private string $image;

    private string $video;

    /**
     * @var array<array-key, string>
     */
    private array $thread;

    private Level $level;

    private TrainingInterface $training;

    /**
     * @param array<array-key, string> $thread
     */
    public static function create(
        Ulid $id,
        DateTimeInterface $publishedAt,
        string $name,
        string $excerpt,
        string $content,
        string $slug,
        string $image,
        string $video,
        array $thread,
        Level $level,
        TrainingInterface $training
    ): self {
        $course = new self();
        $course->id = $id;
        $course->publishedAt = $publishedAt;
        $course->name = $name;
        $course->excerpt = $excerpt;
        $course->content = $content;
        $course->slug = $slug;
        $course->image = $image;
        $course->video = $video;
        $course->thread = $thread;
        $course->level = $level;
        $course->training = $training;

        return $course;
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

    public function excerpt(): string
    {
        return $this->excerpt;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function image(): string
    {
        return $this->image;
    }

    public function video(): string
    {
        return $this->video;
    }

    /**
     * @return array<array-key, string>
     */
    public function thread(): array
    {
        return $this->thread;
    }

    public function level(): Level
    {
        return $this->level;
    }

    public function training(): TrainingInterface
    {
        return $this->training;
    }
}
