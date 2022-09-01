<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path;

use IncentiveFactory\Game\Shared\Entity\PlayerInterface;
use Symfony\Component\Uid\Ulid;

final class Player implements PlayerInterface
{
    private Ulid $id;

    public static function create(Ulid $id): self
    {
        $player = new self();
        $player->id = $id;

        return $player;
    }

    public function id(): Ulid
    {
        return $this->id;
    }
}
