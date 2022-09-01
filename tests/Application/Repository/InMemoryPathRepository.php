<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\Repository;

use DateTimeImmutable;
use IncentiveFactory\Game\Path\Level;
use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\PathGateway;
use Symfony\Component\Uid\Ulid;

final class InMemoryPathRepository implements PathGateway
{
    /**
     * @var array<string, Path>
     */
    public array $paths = [];

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        $this->paths = [
            '01GBWW5FJJ0G3YK3RJM6VWBZBG' => Path::create(
                id: Ulid::fromString('01GBWW5FJJ0G3YK3RJM6VWBZBG'),
                publishedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                slug: 'path-1',
                name: 'Path 1',
                description: 'Description 1',
                level: Level::Easy,
                prerequisites: 'Prerequisites 1',
                skills: 'Skills 1',
                image: 'image.png'
            ),
            '01GBWW5JHNPEXD8S0J5HPT97S2' => Path::create(
                id: Ulid::fromString('01GBWW5JHNPEXD8S0J5HPT97S2'),
                publishedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                slug: 'path-2',
                name: 'Path 2',
                description: 'Description 2',
                level: Level::Medium,
                prerequisites: 'Prerequisites 2',
                skills: 'Skills 2',
                image: 'image.png'
            ),
            '01GBWW96THK59QHW3XESM56RJH' => Path::create(
                id: Ulid::fromString('01GBWW96THK59QHW3XESM56RJH'),
                publishedAt: new DateTimeImmutable('2021-01-01 00:00:00'),
                slug: 'path-3',
                name: 'Path 3',
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
        return array_values($this->paths);
    }

    public function findOneBySlug(string $slug): ?Path
    {
        foreach ($this->paths as $path) {
            if ($path->slug() === $slug) {
                return $path;
            }
        }

        return null;
    }
}
