<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use Generator;
use IncentiveFactory\Game\Player\Gender;
use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Player\Register\NewRegistration;
use IncentiveFactory\Game\Player\Register\Registration;
use IncentiveFactory\Game\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class RegisterTest extends CommandTestCase
{
    public function testShouldRegisterAPlayer(): void
    {
        $this->commandBus->execute(self::createRegistration());

        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);
        $player = $playerGateway->findOneByEmail('player@email.com');

        self::assertInstanceOf(Player::class, $player);
        self::assertSame('player@email.com', $player->email());
        self::assertSame('player', $player->nickname());
        self::assertSame('Password123!', $player->password());
        self::assertSame(Gender::Female, $player->gender());
        self::assertNotNull($player->registrationToken());
        self::assertTrue($this->eventBus->hasDispatched(NewRegistration::class));
    }

    /**
     * @dataProvider provideInvalidRegistrations
     */
    public function testShouldFailedDueToInvalidRegistrationData(Registration $registration): void
    {
        self::expectException(ValidationFailedException::class);
        $this->commandBus->execute($registration);
    }

    /**
     * @return Generator<string, array<array-key, Registration>>
     */
    public function provideInvalidRegistrations(): Generator
    {
        yield 'blank email' => [self::createRegistration(email: '')];
        yield 'invalid email' => [self::createRegistration(email: 'fail')];
        yield 'used email' => [self::createRegistration(email: 'player+1@email.com')];
        yield 'blank nickname' => [self::createRegistration(nickname: '')];
        yield 'blank plainPassword' => [self::createRegistration(plainPassword: '')];
        yield 'invalid plainPassword' => [self::createRegistration(plainPassword: 'fail')];
        yield 'invalid gender' => [self::createRegistration(gender: 'fail')];
    }

    private static function createRegistration(
        string $email = 'player@email.com',
        string $gender = 'Joueuse',
        string $nickname = 'player',
        string $plainPassword = 'Password123!'
    ): Registration {
        $registration = new Registration();
        $registration->email = $email;
        $registration->gender = $gender;
        $registration->nickname = $nickname;
        $registration->plainPassword = $plainPassword;

        return $registration;
    }
}
