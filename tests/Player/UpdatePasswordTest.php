<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Player;

use Closure;
use Generator;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Player\UpdatePassword\NewPassword;
use IncentiveFactory\Domain\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class UpdatePasswordTest extends CommandTestCase
{
    public function testShouldUpdatePasswordOfAPlayer(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var Player $player */
        $player = $playerGateway->getPlayerByEmail('player+1@email.com');

        $this->commandBus->execute(self::createNewPassword()($player));

        /** @var Player $player */
        $player = $playerGateway->getPlayerByEmail('player+1@email.com');

        self::assertSame('NewPassword123!', $player->password());
    }

    /**
     * @dataProvider provideInvalidNewPasswords
     */
    public function testShouldFailedDueToInvalidNewPasswordData(Closure $newPassword): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var Player $player */
        $player = $playerGateway->getPlayerByEmail('player+1@email.com');

        self::expectException(ValidationFailedException::class);
        $this->commandBus->execute($newPassword($player));
    }

    /**
     * @return Generator<string, array<array-key, Closure>>
     */
    public function provideInvalidNewPasswords(): Generator
    {
        yield 'blank plainPassword' => [self::createNewPassword(plainPassword: '')];
        yield 'invalid plainPassword' => [self::createNewPassword(plainPassword: 'fail')];
        yield 'blank oldPassword' => [self::createNewPassword(oldPassword: '')];
        yield 'invalid oldPassword' => [self::createNewPassword(plainPassword: 'fail')];
    }

    private static function createNewPassword(
        string $oldPassword = 'hashed_password',
        string $plainPassword = 'NewPassword123!'
    ): Closure {
        return function (Player $player) use ($oldPassword, $plainPassword): NewPassword {
            $newPassword = new NewPassword($player);
            $newPassword->oldPassword = $oldPassword;
            $newPassword->plainPassword = $plainPassword;

            return $newPassword;
        };
    }
}
