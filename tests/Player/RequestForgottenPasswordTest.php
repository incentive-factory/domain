<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use DateTimeImmutable;
use Generator;
use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Player\RequestForgottenPassword\ForgottenPasswordRequest;
use IncentiveFactory\Game\Player\RequestForgottenPassword\ForgottenPasswordRequested;
use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;
use IncentiveFactory\Game\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class RequestForgottenPasswordTest extends CommandTestCase
{
    public function testShouldCreateForgottenPasswordRequest(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+1@email.com');
        $player->forgotPassword($uuidGenerator->generate());
        $playerGateway->update($player);

        $this->commandBus->execute(self::createForgottenPasswordRequest());

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+1@email.com');

        self::assertNotNull($player->forgottenPasswordExpiredAt());
        self::assertNotNull($player->forgottenPasswordToken());
        self::assertGreaterThan(new DateTimeImmutable(), $player->forgottenPasswordExpiredAt());
        self::assertTrue($this->eventBus->hasDispatched(ForgottenPasswordRequested::class));
    }

    public function testShouldNotCreateForgottenPasswordRequest(): void
    {
        $this->commandBus->execute(self::createForgottenPasswordRequest('fail@email.com'));
        self::assertFalse($this->eventBus->hasDispatched(ForgottenPasswordRequested::class));
    }

    /**
     * @dataProvider provideForgottenPasswordRequests
     */
    public function testShouldFailedDueInvalidForgottenPasswordRequest(ForgottenPasswordRequest $forgottenPasswordRequest): void
    {
        self::expectException(ValidationFailedException::class);
        $this->commandBus->execute($forgottenPasswordRequest);
    }

    /**
     * @return Generator<string, array<array-key, ForgottenPasswordRequest>>
     */
    public function provideForgottenPasswordRequests(): Generator
    {
        yield 'blank email' => [self::createForgottenPasswordRequest('')];
        yield 'invalid email' => [self::createForgottenPasswordRequest('fail')];
    }

    private static function createForgottenPasswordRequest(string $email = 'player+1@email.com'): ForgottenPasswordRequest
    {
        $forgottenPasswordRequest = new ForgottenPasswordRequest();
        $forgottenPasswordRequest->email = $email;

        return $forgottenPasswordRequest;
    }
}
