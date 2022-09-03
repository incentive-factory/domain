<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player;

enum Gender: string
{
    case Female = 'Joueuse';
    case Male = 'Joueur';

    /**
     * @return array<array-key, string>
     */
    public static function all(): array
    {
        return [
            self::Female->value,
            self::Male->value,
        ];
    }
}
