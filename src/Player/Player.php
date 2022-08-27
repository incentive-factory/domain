<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player;

use Symfony\Component\Uid\Ulid;

final class Player
{
    private Ulid $id;

    private string $email;

    private string $nickname;

    private string $password;

    public static function create(Ulid $id, string $email, string $nickname, string $password): self
    {
        $player = new self();
        $player->id = $id;
        $player->email = $email;
        $player->nickname = $nickname;
        $player->password = $password;

        return $player;
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function nickname(): string
    {
        return $this->nickname;
    }

    public function password(): string
    {
        return $this->password;
    }
}
