<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use IncentiveFactory\Domain\Path\CheckIfCourseHasBegun\CourseBegan;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Domain\Tests\QueryTestCase;

class CheckIfCourseHasBegunTest extends QueryTestCase
{
    public function testShouldReturnTrue(): void
    {
        /** @var InMemoryPlayerRepository $playerGateway */
        $playerGateway = $this->container->get(PlayerGateway::class);

        /** @var InMemoryCourseRepository $courseGateway */
        $courseGateway = $this->container->get(CourseGateway::class);

        /** @var bool $pathHasBegun */
        $courseHasBegun = $this->queryBus->fetch(
            new CourseBegan(
                $playerGateway->players['01GBFF6QBSBH7RRTK6N0770BSY'],
                $courseGateway->courses['01GBYMQQK3TY08FEVA0GTJ4QZM']
            )
        );

        self::assertTrue($courseHasBegun);
    }
}
