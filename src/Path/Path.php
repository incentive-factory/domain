<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Path;

use DateTimeInterface;
use IncentiveFactory\Game\Shared\Entity\PlayerInterface;
use IncentiveFactory\Game\Shared\Entity\TrainingInterface;
use Symfony\Component\Uid\Ulid;

final class Path
{
    private Ulid $id;

    private PlayerInterface $player;

    private TrainingInterface $training;

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

    public function training(): TrainingInterface
    {
        return $this->training;
    }

    public function beganAt(): DateTimeInterface
    {
        return $this->beganAt;
    }
}
