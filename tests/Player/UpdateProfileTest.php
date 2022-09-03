<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Player;

use Closure;
use Generator;
use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Player\UpdateProfile\Profile;
use IncentiveFactory\Domain\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class UpdateProfileTest extends CommandTestCase
{
    public function testShouldUpdateProfileOfAPlayer(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+1@email.com');

        $this->commandBus->execute(self::createProfile()($player));

        /** @var ?Player $player */
        $player = $playerGateway->findOneByEmail('player@email.com');

        self::assertInstanceOf(Player::class, $player);
        self::assertSame('player@email.com', $player->email());
        self::assertSame('player', $player->nickname());
        self::assertSame('avatar.png', $player->avatar());
        self::assertSame(Gender::Female, $player->gender());
    }

    /**
     * @dataProvider provideInvalidNewProfiles
     */
    public function testShouldFailedDueToInvalidProfileData(Closure $newProfile): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+1@email.com');

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
        yield 'used email' => [self::createProfile(email: 'player+2@email.com')];
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
