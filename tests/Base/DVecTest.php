<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DVec;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Exception\EmptyCollectionException;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[UsesClass(Freezer::class)]
final class DVecTest extends TestCase
{
    #[Test]
    public function max_throwsOnEmpty(): void
    {
        /** @var DVec<int> $subject */
        $subject = new DVec([]);

        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('Cannot find max() of an empty list.');
        $subject->max();
    }

    #[Test]
    public function max_worksWithoutCallable(): void
    {
        /** @var DVec<int> $subject */
        $subject = new DVec([1, 2, 3]);

        self::assertSame(3, $subject->max());
    }

    #[Test]
    public function max_worksWithCallable(): void
    {
        /** @var DVec<int> $subject */
        $subject = new DVec([1, 2, 3]);

        self::assertSame(4, $subject->max(static fn (int $item) => 5 - $item));
    }

    #[Test]
    public function at(): void
    {
        /** @var DVec<int> $subject */
        $subject = new DVec([10, 20, 30]);

        self::assertSame(20, $subject->at(1));
    }
}
