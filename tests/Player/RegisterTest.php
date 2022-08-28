<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use IncentiveFactory\Game\Player\Register\Register;
use IncentiveFactory\Game\Player\Register\Registration;
use IncentiveFactory\Game\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\Game\Tests\CommandTestCase;
use IncentiveFactory\Game\Tests\Fixtures\InMemoryPlayerRepository;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\Ulid;

final class RegisterTest extends CommandTestCase
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
        $passwordHasher = self::createMock(PasswordHasherInterface::class);
        $passwordHasher
            ->method('hash')
            ->willReturn('hashed_password');

        $ulidGenerator = self::createMock(UlidGeneratorInterface::class);
        $ulidGenerator
            ->method('generate')
            ->willReturn(Ulid::fromString('01GBFAVXKAWNZJYZ6TR4XK4JHV'));

        yield new Register(
            $passwordHasher,
            $ulidGenerator,
            $this->playerGateway,
            $this->eventBus
        );
    }

    public function shouldRegisterPlayer(self $registerTest): void
    {
        $player = $registerTest->playerGateway->players['01GBFAVXKAWNZJYZ6TR4XK4JHV'];

        self::assertSame('01GBFAVXKAWNZJYZ6TR4XK4JHV', (string) $player->id());
        self::assertSame('d8868bcb-31f5-4e95-96ba-a9b6b7a23157', (string) $player->registrationToken());
        self::assertSame('player+2@email.com', $player->email());
        self::assertSame('player+2', $player->nickname());
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
        yield 'non unique email' => ['command' => self::createRegistration(email: 'player+1@email.com')];
        yield 'blank nickname' => ['command' => self::createRegistration(nickname: '')];
        yield 'blank plainPassword' => ['command' => self::createRegistration(plainPassword: '')];
        yield 'invalid plainPassword' => ['command' => self::createRegistration(plainPassword: 'fail')];
    }

    private static function createRegistration(
        string $email = 'player+2@email.com',
        string $nickname = 'player+2',
        string $plainPassword = 'Password123!'
    ): Registration {
        $registration = new Registration();
        $registration->nickname = $nickname;
        $registration->email = $email;
        $registration->plainPassword = $plainPassword;

        return $registration;
    }
}
