<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player;

enum Gender: string
{
    case Female = 'Joueuse';
    case Male = 'Joueur';
}
