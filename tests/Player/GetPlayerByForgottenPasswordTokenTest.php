<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use DateTimeImmutable;
use IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken\ForgottenPasswordToken;
use IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken\ForgottenPasswordTokenExpiredException;
use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;
use IncentiveFactory\Game\Tests\QueryTestCase;
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
        $player = $playerGateway->findOneByEmail('player+0@email.com');
        $player->forgotPassword($uuidGenerator->generate());
        $playerGateway->update($player);

        $player = $this->queryBus->fetch(new ForgottenPasswordToken((string) $player->forgottenPasswordToken()));

        self::assertInstanceOf(Player::class, $player);
    }

    public function testShouldReturnNull(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

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
        $player = $playerGateway->findOneByEmail('player+0@email.com');
        $player->forgotPassword($uuidGenerator->generate());
        $reflectionProperty = new ReflectionProperty($player, 'forgottenPasswordExpiredAt');
        $reflectionProperty->setValue($player, new DateTimeImmutable('1 day ago'));
        $playerGateway->update($player);

        self::expectException(ForgottenPasswordTokenExpiredException::class);

        $this->queryBus->fetch(new ForgottenPasswordToken((string) $player->forgottenPasswordToken()));
    }
}
