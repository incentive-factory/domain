<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\Repository;

use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use Symfony\Component\Uid\Ulid;

final class InMemoryPlayerRepository implements PlayerGateway
{
    /**
     * @var array<string, Player>
     */
    public array $players = [];

    public function __construct()
    {
        $this->init();
    }

    public function init(): void
    {
        $this->players = [
            '01GBJK7XV3YXQ51EHN9G5DAMYN' => Player::create(
                Ulid::fromString('01GBJK7XV3YXQ51EHN9G5DAMYN'),
                'player+0@email.com',
                'player+0',
                'hashed_password'
            ),
            '01GBFF6QBSBH7RRTK6N0770BSY' => Player::create(
                Ulid::fromString('01GBFF6QBSBH7RRTK6N0770BSY'),
                'player+1@email.com',
                'player+1',
                'hashed_password'
            ),
        ];
    }

    public function register(Player $player): void
    {
        $this->players[(string) $player->id()] = $player;
    }

    public function hasEmail(string $email, ?Player $player = null): bool
    {
        return count(
            array_filter(
                $this->players,
                static fn (Player $p) => $p->email() === $email && (null === $player || $p->id() !== $player->id()),
            )
        ) > 0;
    }

    public function update(Player $player): void
    {
        $this->players[(string) $player->id()] = $player;
    }

    public function hasRegistrationToken(string $registrationToken): bool
    {
        return null !== $this->findOneByRegistrationToken($registrationToken);
    }

    public function findOneByRegistrationToken(string $registrationToken): ?Player
    {
        foreach ($this->players as $player) {
            if ((string) $player->registrationToken() === $registrationToken) {
                return $player;
            }
        }

        return null;
    }

    public function findOneByForgottenPasswordToken(string $forgottenPasswordToken): ?Player
    {
        foreach ($this->players as $player) {
            if ((string) $player->forgottenPasswordToken() === $forgottenPasswordToken) {
                return $player;
            }
        }

        return null;
    }

    public function findOneByEmail(string $email): ?Player
    {
        foreach ($this->players as $player) {
            if ($player->email() === $email) {
                return $player;
            }
        }

        return null;
    }
}
