<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\RequestForgottenPassword\ForgottenPasswordRequest;
use IncentiveFactory\Game\Player\RequestForgottenPassword\RequestForgottenPassword;
use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;
use IncentiveFactory\Game\Tests\CommandTestCase;
use IncentiveFactory\Game\Tests\Fixtures\InMemoryPlayerRepository;
use Symfony\Component\Uid\Uuid;

class RequestForgottenPasswordTest extends CommandTestCase
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
        $uuidGenerator = self::createMock(UuidGeneratorInterface::class);
        $uuidGenerator
            ->method('generate')
            ->willReturn(Uuid::fromString('d6f31e80-d4ff-467a-8b47-409715466dae'));

        yield new RequestForgottenPassword(
            $uuidGenerator,
            $this->playerGateway,
            $this->eventBus
        );
    }

    /**
     * @return iterable<string, array{command: ForgottenPasswordRequest, callback: callable}>
     */
    public function provideCommands(): iterable
    {
        /** @var InMemoryPlayerRepository $playerGateway */
        $playerGateway = static::getContainer()->get(InMemoryPlayerRepository::class);

        yield 'request forgotten password' => ['command' => self::createForgottenPasswordRequest(), 'callback' => function () use ($playerGateway) {
            $player = $playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY'];

            self::assertNotNull($player->forgottenPasswordToken());
            self::assertNotNull($player->forgottenPasswordRequestedAt());
        }];
        yield 'fake request forgotten password' => ['command' => self::createForgottenPasswordRequest('player@email.com'), 'callback' => function () use ($playerGateway) {
            self::assertCount(
                0,
                array_filter(
                    $playerGateway->players,
                    static fn (Player $player): bool => null !== $player->forgottenPasswordToken()
                )
            );
        }];
    }

    /**
     * @return iterable<string, array{command: ForgottenPasswordRequest}>
     */
    public function provideInvalidCommands(): iterable
    {
        yield 'blank email' => ['command' => self::createForgottenPasswordRequest('')];
        yield 'invalid email' => ['command' => self::createForgottenPasswordRequest('fail')];
    }

    private static function createForgottenPasswordRequest(string $email = 'player+1@email.com'): ForgottenPasswordRequest
    {
        $forgottenPasswordRequest = new ForgottenPasswordRequest();
        $forgottenPasswordRequest->email = $email;

        return $forgottenPasswordRequest;
    }
}
