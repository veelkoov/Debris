<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Exception\EmptyCollectionException;

/**
 * @internal
 */
#[CoversClass(DList::class)]
final class DListTest extends TestCase
{
    #[Test]
    public function isEmpty_works(): void
    {
        /** @var DList<string> $subject */
        $subject = DList::mut();
        self::assertTrue($subject->isEmpty());

        $subject->add('item');
        self::assertFalse($subject->isEmpty());
    }

    #[Test]
    public function isNotEmpty_works(): void
    {
        /** @var DList<string> $subject */
        $subject = DList::mut();
        self::assertFalse($subject->isNotEmpty());

        $subject->add('item');
        self::assertTrue($subject->isNotEmpty());
    }

    #[Test]
    public function count_works(): void
    {
        /** @var DList<string> $subject */
        $subject = DList::mut();
        self::assertSame(0, $subject->count());

        $subject->add('item');
        self::assertSame(1, $subject->count());
    }

    #[Test]
    public function max_throwsOnEmpty(): void
    {
        /** @var DList<int> $subject */
        $subject = new DList([]);

        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('Cannot find max() of an empty list.');
        $subject->max();
    }

    #[Test]
    public function max_worksWithoutCallable(): void
    {
        /** @var DList<int> $subject */
        $subject = new DList([1, 2, 3]);

        self::assertSame(3, $subject->max());
    }

    #[Test]
    public function max_worksWithCallable(): void
    {
        /** @var DList<int> $subject */
        $subject = new DList([1, 2, 3]);

        self::assertSame(4, $subject->max(static fn (int $item) => 5 - $item));
    }

    #[Test]
    public function at(): void
    {
        /** @var DList<int> $subject */
        $subject = new DList([10, 20, 30]);

        self::assertSame(20, $subject->at(1));
    }

    #[Test]
    public function minusAll(): void
    {
        /** @var DList<int> $subject */
        $subject = new DList([10, 20, 20, 20, 30]);

        $result = $subject->minusAll([20, 20, 30, 40]);

        self::assertSame([10, 20], $result->toArray());
        self::assertSame([10, 20, 20, 20, 30], $subject->toArray());
    }
}
