<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

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
        $this->players['01GBFF6QBSBH7RRTK6N0770BSY'] = Player::create(
            Ulid::fromString('01GBFF6QBSBH7RRTK6N0770BSY'),
            'player+1@email.com',
            'player+1',
            'hashed_password'
        );
    }

    public function register(Player $player): void
    {
        $this->players[(string) $player->id()] = $player;
    }

    public function hasEmail(string $email): bool
    {
        return count(
            array_filter(
                $this->players,
                static fn (Player $player) => $player->email() === $email
            )
        ) > 0;
    }
}
