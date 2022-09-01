<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path;

use IncentiveFactory\Game\Shared\Gateway;

/**
 * @template-extends Gateway<Path>
 */
interface PathGateway extends Gateway
{
    public function begin(Path $path): void;

    public function hasAlreadyBegan(Player $player, Training $training): bool;
}
