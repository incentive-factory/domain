<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests\Path;

use DateTimeImmutable;
use Generator;
use IncentiveFactory\Domain\Path\GetTrainingBySlug\TrainingSlug;
use IncentiveFactory\Domain\Path\Level;
use IncentiveFactory\Domain\Path\Training;
use IncentiveFactory\Domain\Tests\QueryTestCase;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

final class GetTrainingBySlugTest extends QueryTestCase
{
    public function testShouldReturnATrainingByItsSlug(): void
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
     * @dataProvider provideTrainingSlugs
     */
    public function testShouldFailedDueToAInvalidTrainingSlug(TrainingSlug $trainingSlug): void
    {
        self::expectException(ValidationFailedException::class);

        $this->queryBus->fetch($trainingSlug);
    }

    /**
     * @return Generator<string, array<array-key, TrainingSlug>>
     */
    public function provideTrainingSlugs(): Generator
    {
        yield 'blank slug' => [new TrainingSlug('')];
    }
}
