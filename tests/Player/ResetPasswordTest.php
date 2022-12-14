<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Player;

use Closure;
use Generator;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Player\ResetPassword\NewPassword;
use IncentiveFactory\Domain\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class ResetPasswordTest extends CommandTestCase
{
    public function testShouldResetPasswordOfAPlayer(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var Player $player */
        $player = $playerGateway->getPlayerByEmail('player+1@email.com');

        $this->commandBus->execute(self::createNewPassword()($player));

        /** @var Player $player */
        $player = $playerGateway->getPlayerByEmail('player+1@email.com');

        self::assertSame('NewPassword123!', $player->password());
        self::assertNull($player->forgottenPasswordToken());
        self::assertNull($player->forgottenPasswordExpiredAt());
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
    }

    private static function createNewPassword(string $plainPassword = 'NewPassword123!'): Closure
    {
        return function (Player $player) use ($plainPassword): NewPassword {
            $newPassword = new NewPassword($player);
            $newPassword->plainPassword = $plainPassword;

            return $newPassword;
        };
    }
}
