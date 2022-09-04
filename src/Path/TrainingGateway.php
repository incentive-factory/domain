<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path;

use IncentiveFactory\Domain\Shared\Gateway;

/**
 * @template-extends Gateway<Training>
 */
interface TrainingGateway extends Gateway
{
    /**
     * @return array<array-key, Training>
     */
    public function getTrainings(): array;

    public function getTrainingBySlug(string $slug): ?Training;
}
