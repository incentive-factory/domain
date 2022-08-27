<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use IncentiveFactory\Game\Player\UpdatePassword\NewPassword;
use IncentiveFactory\Game\Player\UpdatePassword\UpdatePassword;
use IncentiveFactory\Game\Tests\CommandTestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class UpdatePasswordTest extends CommandTestCase
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
            ->willReturn('new_hashed_password');

        yield new UpdatePassword($passwordHasher, $this->playerGateway);
    }

    public function shouldUpdatePassword(self $registerTest): void
    {
        $player = $registerTest->playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY'];
        self::assertSame('new_hashed_password', $player->password());
    }

    /**
     * @return iterable<string, array{command: NewPassword, callback: callable}>
     */
    public function provideCommands(): iterable
    {
        /** @var callable $callback */
        $callback = [$this, 'shouldUpdatePassword'];

        yield 'register' => [
            'command' => self::createNewPassword(),
            'callback' => $callback,
        ];
    }

    /**
     * @return iterable<string, array{command: NewPassword}>
     */
    public function provideInvalidCommands(): iterable
    {
        yield 'blank plainPassword' => ['command' => self::createNewPassword(plainPassword: '')];
        yield 'invalid plainPassword' => ['command' => self::createNewPassword(plainPassword: 'fail')];
        yield 'wrong oldPassword' => ['command' => self::createNewPassword(oldPassword: 'fail')];
    }

    private static function createNewPassword(
        string $oldPassword = 'hashed_password',
        string $plainPassword = 'Password123!'
    ): NewPassword {
        global $container;
        $playerGateway = $container->get(InMemoryPlayerRepository::class);
        $newPassword = new NewPassword($playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY']);
        $newPassword->oldPassword = $oldPassword;
        $newPassword->plainPassword = $plainPassword;

        return $newPassword;
    }
}
