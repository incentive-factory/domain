<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Application\Repository;

use DateTimeImmutable;
use IncentiveFactory\Domain\Path\Level;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Path\TrainingGateway;
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

    public static function createTraining(int $index, string $ulid): Training
    {
        return Training::create(
            id: Ulid::fromString($ulid),
            publishedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
            slug: sprintf('training-%d', $index),
            name: sprintf('Training %d', $index),
            description: 'Description',
            level: Level::Easy,
            prerequisites: 'Prerequisite',
            skills: 'Skill',
            image: 'image.png'
        );
    }

    public function init(): void
    {
        $this->trainings = [
            '01GBWW5FJJ0G3YK3RJM6VWBZBG' => self::createTraining(1, '01GBWW5FJJ0G3YK3RJM6VWBZBG'),
            '01GBWW5JHNPEXD8S0J5HPT97S2' => self::createTraining(2, '01GBWW5JHNPEXD8S0J5HPT97S2'),
            '01GBWW96THK59QHW3XESM56RJH' => self::createTraining(3, '01GBWW96THK59QHW3XESM56RJH'),
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
