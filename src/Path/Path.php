<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Path;

use DateTimeInterface;
use IncentiveFactory\Domain\Shared\Entity\PlayerInterface;
use Symfony\Component\Uid\Ulid;

final class Path
{
    private Ulid $id;

    private PlayerInterface $player;

    private Training $training;

    private DateTimeInterface $beganAt;

    public static function create(Ulid $id, PlayerInterface $player, Training $training, DateTimeInterface $beganAt): self
    {
        $registration = new self();
        $registration->id = $id;
        $registration->player = $player;
        $registration->training = $training;
        $registration->beganAt = $beganAt;

        return $registration;
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function player(): PlayerInterface
    {
        return $this->player;
    }

    public function training(): Training
    {
        return $this->training;
    }

    public function beganAt(): DateTimeInterface
    {
        return $this->beganAt;
    }
}
