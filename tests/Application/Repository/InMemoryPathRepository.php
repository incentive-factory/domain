<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Application\Repository;

use DateTimeImmutable;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;
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

    public static function createPath(string $id, PlayerInterface $player, Training $training): Path
    {
        return Path::create(
            id: Ulid::fromString($id),
            player: $player,
            training: $training,
            beganAt: new DateTimeImmutable('2021-01-01 00:00:00')
        );
    }

    public function init(): void
    {
        $this->paths = [
            '01GBXF8ATAE03HY5ZC3ES90122' => self::createPath(
                id: '01GBXF8ATAE03HY5ZC3ES90122',
                player: InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY'),
                training: InMemoryTrainingRepository::createTraining(1, '01GBWW5FJJ0G3YK3RJM6VWBZBG')
            ),
            '01GBXF8EPC06PV81J70Z0ACKCC' => self::createPath(
                id: '01GBXF8EPC06PV81J70Z0ACKCC',
                player: InMemoryPlayerRepository::createPlayer(1, '01GBFF6QBSBH7RRTK6N0770BSY'),
                training: InMemoryTrainingRepository::createTraining(2, '01GBWW5JHNPEXD8S0J5HPT97S2')
            ),
        ];
    }

    public function begin(Path $path): void
    {
        $this->paths[(string) $path->id()] = $path;
    }

    public function complete(Path $path): void
    {
        $this->paths[(string) $path->id()] = $path;
    }

    public function hasAlreadyBegun(PlayerInterface $player, Training $training): bool
    {
        foreach ($this->paths as $path) {
            if ($path->player()->id()->equals($player->id()) && $path->training()->id()->equals($training->id())) {
                return true;
            }
        }

        return false;
    }

    public function getPathsByPlayer(PlayerInterface $player): array
    {
        $paths = [];
        foreach ($this->paths as $path) {
            if ($path->player()->id()->equals($player->id())) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    public function getPathById(string $id): ?Path
    {
        return $this->paths[$id] ?? null;
    }
}
