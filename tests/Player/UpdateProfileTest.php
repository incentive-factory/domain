<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use IncentiveFactory\Game\Player\UpdateProfile\Profile;
use IncentiveFactory\Game\Player\UpdateProfile\UpdateProfile;
use IncentiveFactory\Game\Tests\CommandTestCase;
use IncentiveFactory\Game\Tests\Fixtures\InMemoryPlayerRepository;

final class UpdateProfileTest extends CommandTestCase
{
    private InMemoryPlayerRepository $playerGateway;

    protected function setUp(): void
    {
        /* @phpstan-ignore-next-line */
        $this->playerGateway = $this->getContainer()->get(InMemoryPlayerRepository::class);

        parent::setUp();
    }

    protected function registerHandlers(): iterable
    {
        yield new UpdateProfile($this->playerGateway);
    }

    public function shouldUpdateProfile(self $registerTest): void
    {
        $player = $registerTest->playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY'];

        self::assertSame('player+2@email.com', $player->email());
        self::assertSame('player+2', $player->nickname());
        self::assertSame('avatar.png', $player->avatar());
    }

    /**
     * @return iterable<string, array{command: Profile, callback: callable}>
     */
    public function provideCommands(): iterable
    {
        /** @var callable $callback */
        $callback = [$this, 'shouldUpdateProfile'];

        yield 'update profile' => ['command' => self::createProfile(), 'callback' => $callback];
    }

    /**
     * @return iterable<string, array{command: Profile}>
     */
    public function provideInvalidCommands(): iterable
    {
        yield 'blank email' => ['command' => self::createProfile(email: '')];
        yield 'invalid email' => ['command' => self::createProfile(email: 'fail')];
        yield 'non unique email' => ['command' => self::createProfile(email: 'player+0@email.com')];
        yield 'blank nickname' => ['command' => self::createProfile(nickname: '')];
    }

    private static function createProfile(
        string $email = 'player+2@email.com',
        string $nickname = 'player+2',
        string $avatar = 'avatar.png'
    ): Profile {
        /** @var InMemoryPlayerRepository $playerGateway */
        $playerGateway = static::getContainer()->get(InMemoryPlayerRepository::class);
        $profile = new Profile($playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY']);
        $profile->nickname = $nickname;
        $profile->email = $email;
        $profile->avatar = $avatar;

        return $profile;
    }
}
