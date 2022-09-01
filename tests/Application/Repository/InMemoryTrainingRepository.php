<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\Repository;

use DateTimeImmutable;
use IncentiveFactory\Game\Path\Level;
use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Path\TrainingGateway;
use Symfony\Component\Uid\Ulid;

final class InMemoryTrainingRepository implements TrainingGateway
{
    /**
     * @var array<string, Training>
     */
    public array $trainings = [];

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        $this->trainings = [
            '01GBWW5FJJ0G3YK3RJM6VWBZBG' => Training::create(
                id: Ulid::fromString('01GBWW5FJJ0G3YK3RJM6VWBZBG'),
                publishedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                slug: 'training-1',
                name: 'Training 1',
                description: 'Description 1',
                level: Level::Easy,
                prerequisites: 'Prerequisites 1',
                skills: 'Skills 1',
                image: 'image.png'
            ),
            '01GBWW5JHNPEXD8S0J5HPT97S2' => Training::create(
                id: Ulid::fromString('01GBWW5JHNPEXD8S0J5HPT97S2'),
                publishedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                slug: 'training-2',
                name: 'Training 2',
                description: 'Description 2',
                level: Level::Medium,
                prerequisites: 'Prerequisites 2',
                skills: 'Skills 2',
                image: 'image.png'
            ),
            '01GBWW96THK59QHW3XESM56RJH' => Training::create(
                id: Ulid::fromString('01GBWW96THK59QHW3XESM56RJH'),
                publishedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                slug: 'training-3',
                name: 'Training 3',
                description: 'Description 3',
                level: Level::Hard,
                prerequisites: 'Prerequisites 3',
                skills: 'Skills 3',
                image: 'image.png'
            ),
        ];
    }

    public function findAll(): array
    {
        return array_values($this->trainings);
    }

    public function findOneBySlug(string $slug): ?Training
    {
        foreach ($this->trainings as $training) {
            if ($training->slug() === $slug) {
                return $training;
            }
        }

        return null;
    }
}
