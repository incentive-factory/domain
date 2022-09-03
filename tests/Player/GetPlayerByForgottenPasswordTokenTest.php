<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Player;

use DateTimeImmutable;
use IncentiveFactory\Domain\Player\GetPlayerByForgottenPasswordToken\ForgottenPasswordToken;
use IncentiveFactory\Domain\Player\GetPlayerByForgottenPasswordToken\ForgottenPasswordTokenExpiredException;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Shared\Uid\UuidGeneratorInterface;
use IncentiveFactory\Domain\Tests\QueryTestCase;
use ReflectionProperty;

final class GetPlayerByForgottenPasswordTokenTest extends QueryTestCase
{
    public function testShouldReturnAPlayerByItsForgottenPasswordToken(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+1@email.com');
        $player->forgotPassword($uuidGenerator->generate());
        $playerGateway->update($player);

        $player = $this->queryBus->fetch(new ForgottenPasswordToken((string) $player->forgottenPasswordToken()));

        self::assertInstanceOf(Player::class, $player);
    }

    public function testShouldReturnNull(): void
    {
        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

        $player = $this->queryBus->fetch(new ForgottenPasswordToken((string) $uuidGenerator->generate()));

        self::assertNull($player);
    }

    public function testShouldFailedDueToAExpiredForgottenPasswordRequest(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+1@email.com');
        $player->forgotPassword($uuidGenerator->generate());
        $reflectionProperty = new ReflectionProperty($player, 'forgottenPasswordExpiredAt');
        $reflectionProperty->setValue($player, new DateTimeImmutable('1 day ago'));
        $playerGateway->update($player);

        self::expectException(ForgottenPasswordTokenExpiredException::class);

        $this->queryBus->fetch(new ForgottenPasswordToken((string) $player->forgottenPasswordToken()));
    }
}
