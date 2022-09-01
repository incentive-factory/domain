<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player;

enum Gender: string
{
    case JOUEUSE = 'Joueuse';
    case JOUEUR = 'Joueur';

    /**
     * @return array<array-key, string>
     */
    public static function all(): array
    {
        return [
            self::JOUEUSE->value,
            self::JOUEUR->value,
        ];
    }
}
