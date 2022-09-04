<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\CourseGateway;
use IncentiveFactory\Domain\Path\CourseLog;
use IncentiveFactory\Domain\Path\GetCourseLogByPathAndCourse\CourseLogPathAndCourse;
use IncentiveFactory\Domain\Path\Path;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPathRepository;
use IncentiveFactory\Domain\Tests\QueryTestCase;

final class GetCourseLogByPathAndCourseTest extends QueryTestCase
{
    public function testShouldReturnCourseLog(): void
    {
        /** @var InMemoryCourseRepository $courseGateway */
        $courseGateway = $this->container->get(CourseGateway::class);

        /** @var InMemoryPathRepository $pathGateway */
        $pathGateway = $this->container->get(PathGateway::class);

        /** @var Path $path */
        $path = $pathGateway->paths['01GBXF8ATAE03HY5ZC3ES90122'];

        /** @var Course $course */
        $course = $courseGateway->courses['01GBYMQQK3TY08FEVA0GTJ4QZM'];

        /** @var ?CourseLog $courseLog */
        $courseLog = $this->queryBus->fetch(new CourseLogPathAndCourse($path, $course));

        self::assertNotNull($courseLog);
    }

    public function testShouldReturnNull(): void
    {
        /** @var InMemoryCourseRepository $courseGateway */
        $courseGateway = $this->container->get(CourseGateway::class);

        /** @var InMemoryPathRepository $pathGateway */
        $pathGateway = $this->container->get(PathGateway::class);

        /** @var Path $path */
        $path = $pathGateway->paths['01GBXF8EPC06PV81J70Z0ACKCC'];

        /** @var Course $course */
        $course = $courseGateway->courses['01GBYN0SWAMB7N272PW7G1VDF0'];

        /** @var ?CourseLog $courseLog */
        $courseLog = $this->queryBus->fetch(new CourseLogPathAndCourse($path, $course));

        self::assertNull($courseLog);
    }
}
