<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use Closure;
use Generator;
use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Player\ResetPassword\NewPassword;
use IncentiveFactory\Game\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class ResetPasswordTest extends CommandTestCase
{
    public function testShouldResetPasswordOfAPlayer(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+0@email.com');

        $this->commandBus->execute(self::createNewPassword()($player));

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+0@email.com');

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
        $player = $playerGateway->findOneByEmail('player+0@email.com');

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
