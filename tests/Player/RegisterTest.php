<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use IncentiveFactory\Game\Player\Register\Register;
use IncentiveFactory\Game\Player\Register\Registration;
use IncentiveFactory\Game\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\Game\Tests\CommandTestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Ulid;

final class RegisterTest extends CommandTestCase
{
    private InMemoryPlayerRepository $playerGateway;

    protected function setUp(): void
    {
        $this->playerGateway = new InMemoryPlayerRepository();

        parent::setUp();
    }

    protected function registerHandlers(): iterable
    {
        $passwordHasher = self::createMock(PasswordHasherInterface::class);
        $passwordHasher
            ->method('hash')
            ->willReturn('hashed_password');

        $ulidGenerator = self::createMock(UlidGeneratorInterface::class);
        $ulidGenerator
            ->method('generate')
            ->willReturn(Ulid::fromString('01GBFAVXKAWNZJYZ6TR4XK4JHV'));

        yield new Register($passwordHasher, $ulidGenerator, $this->playerGateway);
    }

    public function shouldRegisterPlayer(self $registerTest): void
    {
        $player = $registerTest->playerGateway->players[0];

        self::assertSame('01GBFAVXKAWNZJYZ6TR4XK4JHV', (string) $player->id());
        self::assertSame('player@email.com', $player->email());
        self::assertSame('player', $player->nickname());
        self::assertSame('hashed_password', $player->password());
    }

    /**
     * @return iterable<string, array{command: Registration, callback: callable}>
     */
    public function provideCommands(): iterable
    {
        /** @var callable $callback */
        $callback = [$this, 'shouldRegisterPlayer'];

        yield 'register' => ['command' => self::createRegistration(), 'callback' => $callback];
    }

    /**
     * @return iterable<string, array{command: Registration}>
     */
    public function provideInvalidCommands(): iterable
    {
        yield 'blank email' => ['command' => self::createRegistration(email: '')];
        yield 'invalid email' => ['command' => self::createRegistration(email: 'fail')];
        yield 'blank nickname' => ['command' => self::createRegistration(nickname: '')];
        yield 'blank plainPassword' => ['command' => self::createRegistration(plainPassword: '')];
        yield 'invalid plainPassword' => ['command' => self::createRegistration(plainPassword: 'fail')];
    }

    private static function createRegistration(
        string $email = 'player@email.com',
        string $nickname = 'player',
        string $plainPassword = 'Password123!'
    ): Registration {
        $registration = new Registration();
        $registration->nickname = $nickname;
        $registration->email = $email;
        $registration->plainPassword = $plainPassword;

        return $registration;
    }
}
