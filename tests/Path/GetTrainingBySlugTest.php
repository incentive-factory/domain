<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Path;

use DateTimeImmutable;
use Generator;
use IncentiveFactory\Game\Path\GetTrainingBySlug\TrainingSlug;
use IncentiveFactory\Game\Path\Level;
use IncentiveFactory\Game\Path\Training;
use IncentiveFactory\Game\Tests\QueryTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class GetTrainingBySlugTest extends QueryTestCase
{
    public function testShouldReturnAPathByItsSlug(): void
    {
        /** @var ?Training $training */
        $training = $this->queryBus->fetch(new TrainingSlug('training-1'));

        self::assertInstanceOf(Training::class, $training);
        self::assertSame('training-1', $training->slug());
        self::assertSame('Training 1', $training->name());
        self::assertSame('Description', $training->description());
        self::assertSame(Level::Easy, $training->level());
        self::assertSame('Prerequisite', $training->prerequisites());
        self::assertSame('Skill', $training->skills());
        self::assertSame('image.png', $training->image());
        self::assertEquals(new DateTimeImmutable('2021-01-01 00:00:00'), $training->publishedAt());
        self::assertSame('01GBWW5FJJ0G3YK3RJM6VWBZBG', (string) $training->id());
    }

    public function testShouldReturnNull(): void
    {
        /** @var ?Training $training */
        $training = $this->queryBus->fetch(new TrainingSlug('training-0'));

        self::assertNull($training);
    }

    /**
     * @dataProvider providePathSlugs
     */
    public function testShouldFailedDueToAInvalidPathSlug(TrainingSlug $trainingSlug): void
    {
        self::expectException(ValidationFailedException::class);

        $this->queryBus->fetch($trainingSlug);
    }

    /**
     * @return Generator<string, array<array-key, TrainingSlug>>
     */
    public function providePathSlugs(): Generator
    {
        yield 'blank slug' => [new TrainingSlug('')];
    }
}
