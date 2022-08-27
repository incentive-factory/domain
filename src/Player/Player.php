<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

final class Player
{
    private Ulid $id;

    private string $email;

    private string $nickname;

    private string $password;

    private ?Uuid $registrationToken = null;

    private ?DateTimeInterface $registeredAt = null;

    public static function create(
        Ulid $id,
        string $email,
        string $nickname,
        string $password,
        ?Uuid $registrationToken = null
    ): self {
        $player = new self();
        $player->id = $id;
        $player->email = $email;
        $player->nickname = $nickname;
        $player->password = $password;
        $player->registrationToken = $registrationToken;

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

    public function registrationToken(): ?Uuid
    {
        return $this->registrationToken;
    }

    public function registeredAt(): ?DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function prepareValidationOfRegistration(?Uuid $registrationToken): void
    {
        $this->registrationToken = $registrationToken;
    }

    public function validateRegistration(): void
    {
        $this->registrationToken = null;
        $this->registeredAt = new DateTimeImmutable();
    }

    public function newPassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }
}
