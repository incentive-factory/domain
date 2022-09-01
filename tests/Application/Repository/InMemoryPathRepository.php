<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\Repository;

use IncentiveFactory\Game\Path\Path;
use IncentiveFactory\Game\Path\PathGateway;
use IncentiveFactory\Game\Path\Player;
use IncentiveFactory\Game\Path\Training;

final class InMemoryPathRepository implements PathGateway
{
    /**
     * @var array<string, Path>
     */
    public array $paths = [];

    public function begin(Path $path): void
    {
        $this->paths[(string) $path->id()] = $path;
    }

    public function hasAlreadyBegan(Player $player, Training $training): bool
    {
        foreach ($this->paths as $path) {
            if ($path->player()->id()->equals($player->id()) && $path->path()->id()->equals($training->id())) {
                return true;
            }
        }

        return false;
    }
}
