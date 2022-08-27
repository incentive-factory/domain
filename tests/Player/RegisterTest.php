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
            ->expects(self::once())
            ->method('hash')
            ->willReturn('hashed_password');

        $ulidGenerator = self::createMock(UlidGeneratorInterface::class);
        $ulidGenerator
            ->expects(self::once())
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
        $registration = new Registration();
        $registration->nickname = 'player';
        $registration->email = 'player@email.com';
        $registration->plainPassword = 'Password123!';

        /** @var callable $callback */
        $callback = [$this, 'shouldRegisterPlayer'];

        yield 'register' => ['command' => $registration, 'callback' => $callback];
    }
}
