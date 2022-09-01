<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path;

use DateTimeInterface;
use Symfony\Component\Uid\Ulid;

final class Path
{
    private Ulid $id;

    private Player $player;

    private Training $path;

    private DateTimeInterface $beganAt;

    public static function create(Ulid $id, Player $player, Training $path, DateTimeInterface $beganAt): self
    {
        $registration = new self();
        $registration->id = $id;
        $registration->player = $player;
        $registration->path = $path;
        $registration->beganAt = $beganAt;

        return $registration;
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function player(): Player
    {
        return $this->player;
    }

    public function path(): Training
    {
        return $this->path;
    }

    public function beganAt(): DateTimeInterface
    {
        return $this->beganAt;
    }
}
