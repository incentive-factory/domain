<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path;

use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;
use IncentiveFactory\Domain\Shared\Gateway;

/**
 * @template-extends Gateway<Path>
 */
interface PathGateway extends Gateway
{
    public function begin(Path $path): void;

    public function complete(Path $path): void;

    public function hasAlreadyBegun(PlayerInterface $player, Training $training): bool;

    /**
     * @return array<array-key, Path>
     */
    public function getPathsByPlayer(PlayerInterface $player): array;

    public function getPathById(string $id): ?Path;
}
