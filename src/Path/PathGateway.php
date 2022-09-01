<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path;

use IncentiveFactory\Game\Shared\Gateway;

/**
 * @template-extends Gateway<Path>
 */
interface PathGateway extends Gateway
{
    /**
     * @return array<array-key, Path>
     */
    public function findAll(): array;

    public function findOneBySlug(string $slug): ?Path;
}
