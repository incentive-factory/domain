<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use IncentiveFactory\Game\Player\ValidRegistration\ValidationOfRegistration;
use IncentiveFactory\Game\Player\ValidRegistration\ValidRegistration;
use IncentiveFactory\Game\Tests\CommandTestCase;
use IncentiveFactory\Game\Tests\Fixtures\InMemoryPlayerRepository;
use Symfony\Component\Uid\Uuid;

class ValidRegistrationTest extends CommandTestCase
{
    private InMemoryPlayerRepository $playerGateway;

    protected function setUp(): void
    {
        /* @phpstan-ignore-next-line */
        $this->playerGateway = $this->getContainer()->get(InMemoryPlayerRepository::class);

        /* @phpstan-ignore-next-line */
        $this->playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY']->prepareValidationOfRegistration(
            Uuid::fromString('8fea5b35-22a9-44c7-a25c-52b105656a28')
        );

        parent::setUp();
    }

    protected function registerHandlers(): iterable
    {
        yield new ValidRegistration($this->playerGateway);
    }

    public function shouldValidRegistrationPlayer(self $registerTest): void
    {
        $player = $registerTest->playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY'];

        self::assertNull($player->registrationToken());
    }

    /**
     * @return iterable<string, array{command: ValidationOfRegistration, callback: callable}>
     */
    public function provideCommands(): iterable
    {
        /** @var callable $callback */
        $callback = [$this, 'shouldValidRegistrationPlayer'];

        yield 'valid registration' => ['command' => self::createValidationOfRegistration(), 'callback' => $callback];
    }

    /**
     * @return iterable<string, array{command: ValidationOfRegistration}>
     */
    public function provideInvalidCommands(): iterable
    {
        yield 'blank registrationToken' => ['command' => self::createValidationOfRegistration('')];
        yield 'invalid registrationToken' => ['command' => self::createValidationOfRegistration('fail')];
        yield 'non existing registrationToken' => ['command' => self::createValidationOfRegistration('bdb02f18-246c-4ca6-85b8-d72f7dd7034d')];
    }

    private static function createValidationOfRegistration(
        string $registrationToken = '8fea5b35-22a9-44c7-a25c-52b105656a28'
    ): ValidationOfRegistration {
        return new ValidationOfRegistration($registrationToken);
    }
}
