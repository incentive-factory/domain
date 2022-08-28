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

    private ?string $avatar = null;

    private ?Uuid $registrationToken = null;

    private ?Uuid $forgottenPasswordToken = null;

    private ?DateTimeInterface $forgottenPasswordRequestedAt = null;

    private ?DateTimeInterface $registeredAt = null;

    public static function create(
        Ulid $id,
        string $email,
        string $nickname,
        string $password,
        ?string $avatar = null,
        ?Uuid $registrationToken = null
    ): self {
        $player = new self();
        $player->id = $id;
        $player->email = $email;
        $player->nickname = $nickname;
        $player->password = $password;
        $player->avatar = $avatar;
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

    public function avatar(): ?string
    {
        return $this->avatar;
    }

    public function registrationToken(): ?Uuid
    {
        return $this->registrationToken;
    }

    public function registeredAt(): ?DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function forgottenPasswordToken(): ?Uuid
    {
        return $this->forgottenPasswordToken;
    }

    public function forgottenPasswordRequestedAt(): ?DateTimeInterface
    {
        return $this->forgottenPasswordRequestedAt;
    }

    public function update(string $email, string $nickname, ?string $avatar = null): void
    {
        $this->email = $email;
        $this->nickname = $nickname;
        $this->avatar = $avatar;
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

    public function forgotPassword(Uuid $forgottenPasswordToken): void
    {
        $this->forgottenPasswordToken = $forgottenPasswordToken;
        $this->forgottenPasswordRequestedAt = new DateTimeImmutable();
    }
}
