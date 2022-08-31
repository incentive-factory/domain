<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Player;

use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Player\ValidRegistration\ValidationOfRegistration;
use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;
use IncentiveFactory\Game\Tests\CommandTestCase;
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
        $player = $playerGateway->findOneByEmail('player+0@email.com');
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
