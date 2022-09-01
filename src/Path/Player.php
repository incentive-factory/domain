<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path;

use Symfony\Component\Uid\Ulid;

final class Player
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
