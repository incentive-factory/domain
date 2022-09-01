<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use Closure;
use Generator;
use IncentiveFactory\Game\Player\Gender;
use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Player\UpdateProfile\Profile;
use IncentiveFactory\Game\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class UpdateProfileTest extends CommandTestCase
{
    public function testShouldUpdateProfileOfAPlayer(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+0@email.com');

        $this->commandBus->execute(self::createProfile()($player));

        /** @var ?Player $player */
        $player = $playerGateway->findOneByEmail('player@email.com');

        self::assertInstanceOf(Player::class, $player);
        self::assertSame('player@email.com', $player->email());
        self::assertSame('player', $player->nickname());
        self::assertSame('avatar.png', $player->avatar());
        self::assertSame(Gender::JOUEUSE, $player->gender());
    }

    /**
     * @dataProvider provideInvalidNewProfiles
     */
    public function testShouldFailedDueToInvalidProfileData(Closure $newProfile): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+0@email.com');

        self::expectException(ValidationFailedException::class);
        $this->commandBus->execute($newProfile($player));
    }

    /**
     * @return Generator<string, array<array-key, Closure>>
     */
    public function provideInvalidNewProfiles(): Generator
    {
        yield 'blank email' => [self::createProfile(email: '')];
        yield 'invalid email' => [self::createProfile(email: 'fail')];
        yield 'invalid gender' => [self::createProfile(gender: 'fail')];
        yield 'used email' => [self::createProfile(email: 'player+1@email.com')];
        yield 'blank nickname' => [self::createProfile(nickname: '')];
    }

    private static function createProfile(
        string $gender = 'Joueuse',
        string $email = 'player@email.com',
        string $nickname = 'player',
        string $avatar = 'avatar.png'
    ): Closure {
        return function (Player $player) use ($gender, $email, $nickname, $avatar): Profile {
            $profile = new Profile($player);
            $profile->email = $email;
            $profile->gender = $gender;
            $profile->nickname = $nickname;
            $profile->avatar = $avatar;

            return $profile;
        };
    }
}
