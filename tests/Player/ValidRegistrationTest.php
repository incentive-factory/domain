<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Player;

use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Player\ValidRegistration\ValidationOfRegistration;
use IncentiveFactory\Domain\Shared\Uid\UuidGeneratorInterface;
use IncentiveFactory\Domain\Tests\CommandTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class ValidRegistrationTest extends CommandTestCase
{
    public function testShouldValidRegistrationOfAPlayer(): void
    {
        /** @var PlayerGateway $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

        /** @var Player $player */
        $player = $playerGateway->findOneByEmail('player+1@email.com');
        $player->prepareValidationOfRegistration($uuidGenerator->generate());
        $playerGateway->update($player);

        $this->commandBus->execute(new ValidationOfRegistration((string) $player->registrationToken()));

        self::assertNull($player->registrationToken());
        self::assertNotNull($player->registeredAt());
    }

    public function testShouldFailedDueToUnexistingRegistrationToken(): void
    {
        /** @var UuidGeneratorInterface $uuidGenerator */
        $uuidGenerator = $this->container->get(UuidGeneratorInterface::class);

        self::expectException(ValidationFailedException::class);

        $this->commandBus->execute(new ValidationOfRegistration((string) $uuidGenerator->generate()));
    }
}
