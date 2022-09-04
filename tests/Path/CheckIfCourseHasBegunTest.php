<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use IncentiveFactory\Domain\Path\CheckIfCourseHasBegun\CourseBegan;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPathRepository;
use IncentiveFactory\Domain\Tests\QueryTestCase;

class CheckIfCourseHasBegunTest extends QueryTestCase
{
    public function testShouldReturnTrue(): void
    {
        /** @var InMemoryPathRepository $pathGateway */
        $pathGateway = $this->container->get(PathGateway::class);

        /** @var InMemoryCourseRepository $courseGateway */
        $courseGateway = $this->container->get(CourseGateway::class);

        /** @var bool $pathHasBegun */
        $courseHasBegun = $this->queryBus->fetch(
            new CourseBegan(
                $pathGateway->paths['01GBXF8ATAE03HY5ZC3ES90122'],
                $courseGateway->courses['01GBYMQQK3TY08FEVA0GTJ4QZM']
            )
        );

        self::assertTrue($courseHasBegun);
    }
}
