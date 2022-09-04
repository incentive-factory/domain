<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use DateTimeImmutable;
use Generator;
use IncentiveFactory\Domain\Path\Course;
use IncentiveFactory\Domain\Path\GetCourseBySlug\CourseSlug;
use IncentiveFactory\Domain\Path\Level;
use IncentiveFactory\Domain\Tests\QueryTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class GetCourseBySlugTest extends QueryTestCase
{
    public function testShouldReturnAPathByItsSlug(): void
    {
        /** @var ?Course $course */
        $course = $this->queryBus->fetch(new CourseSlug('course-1'));

        self::assertInstanceOf(Course::class, $course);
        self::assertSame('course-1', $course->slug());
        self::assertSame('Course 1', $course->name());
        self::assertSame('Content', $course->content());
        self::assertSame(Level::Easy, $course->level());
        self::assertSame(['tweet'], $course->thread());
        self::assertSame('Excerpt', $course->excerpt());
        self::assertSame('image.png', $course->image());
        self::assertSame('https://youtu.be/ABCDEFGH', $course->video());
        self::assertSame('01GBWW5FJJ0G3YK3RJM6VWBZBG', (string) $course->training()->id());
        self::assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $course->publishedAt());
        self::assertSame('01GBYMQQK3TY08FEVA0GTJ4QZM', (string) $course->id());
    }

    public function testShouldReturnNull(): void
    {
        /** @var ?Course $course */
        $course = $this->queryBus->fetch(new CourseSlug('course-0'));

        self::assertNull($course);
    }

    /**
     * @dataProvider providePathSlugs
     */
    public function testShouldFailedDueToAInvalidPathSlug(CourseSlug $courseSlug): void
    {
        self::expectException(ValidationFailedException::class);

        $this->queryBus->fetch($courseSlug);
    }

    /**
     * @return Generator<string, array<array-key, CourseSlug>>
     */
    public function providePathSlugs(): Generator
    {
        yield 'blank slug' => [new CourseSlug('')];
    }
}
