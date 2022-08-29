<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use DateTimeImmutable;
use IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken\ForgottenPasswordToken;
use IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken\ForgottenPasswordTokenExpiredException;
use IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken\ForgottenPasswordTokenNotFoundException;
use IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken\GetPlayerByForgottenPasswordToken;
use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Tests\Fixtures\InMemoryPlayerRepository;
use IncentiveFactory\Game\Tests\QueryTestCase;
use ReflectionProperty;
use Symfony\Component\Uid\Uuid;

final class GetPlayerByForgottenPasswordTokenTest extends QueryTestCase
{
    protected function registerHandlers(): iterable
    {
        /** @var InMemoryPlayerRepository $playerGateway */
        $playerGateway = static::getContainer()->get(InMemoryPlayerRepository::class);

        yield new GetPlayerByForgottenPasswordToken($playerGateway);
    }

    public function provideQueries(): iterable
    {
        $token = Uuid::v4();

        yield 'get player by forgotten password token' => [
            'query' => new ForgottenPasswordToken((string) $token),
            'init' => function (self $testCase) use ($token) {
                /** @var InMemoryPlayerRepository $playerGateway */
                $playerGateway = static::getContainer()->get(InMemoryPlayerRepository::class);
                $player = $playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY'];
                $player->forgotPassword($token);
                $playerGateway->update($player);
            },
            'callback' => function (self $testCase, ?Player $player) {
                self::assertNotNull($player);
            },
        ];
    }

    public function provideInvalidQueries(): iterable
    {
        yield 'blank token' => [
            'query' => new ForgottenPasswordToken(''),
            'init' => fn () => null,
        ];

        yield 'invalid token' => [
            'query' => new ForgottenPasswordToken('fail'),
            'init' => fn () => null,
        ];
    }

    public function provideFailedQueries(): iterable
    {
        yield 'token not found' => [
            'query' => new ForgottenPasswordToken((string) Uuid::v4()),
            'init' => fn () => null,
            'exceptionClass' => ForgottenPasswordTokenNotFoundException::class,
        ];

        $token = Uuid::v4();

        yield 'token expired' => [
            'query' => new ForgottenPasswordToken((string) $token),
            'init' => function () use ($token) {
                /** @var InMemoryPlayerRepository $playerGateway */
                $playerGateway = static::getContainer()->get(InMemoryPlayerRepository::class);

                $player = $playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY'];

                $player->forgotPassword($token);

                $playerGateway->update($player);

                $reflectionProperty = new ReflectionProperty(Player::class, 'forgottenPasswordRequestedAt');
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($player, new DateTimeImmutable('-1 day'));

                $playerGateway->update($player);
            },
            'exceptionClass' => ForgottenPasswordTokenExpiredException::class,
        ];
    }
}
