<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Player;

use Generator;
use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Player\Register\NewRegistration;
use IncentiveFactory\Domain\Player\Register\Registration;
use IncentiveFactory\Domain\Tests\CommandTestCase;
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
        self::assertFalse($player->hasValidRegistration());
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
    }

    private static function createRegistration(
        string $email = 'player@email.com',
        Gender $gender = Gender::Female,
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
