<?php

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\DList;
use Veelkoov\Debris\EmptyCollectionException;

#[CoversClass(DList::class)]
class DListTest extends TestCase
{
    #[Test]
    public function maxOnEmptyThrows(): void
    {
        /** @var DList<int> $subject */
        $subject = new DList([]);

        $this->expectException(EmptyCollectionException::class);
        $this->expectExceptionMessage('Cannot find max() of an empty list.');
        $subject->max();
    }

    #[Test]
    public function maxWorksWithoutCallback(): void
    {
        /** @var DList<int> $subject */
        $subject = new DList([1, 2, 3]);

        self::assertSame(3, $subject->max());
    }

    #[Test]
    public function maxWorksWithCallbackWorks(): void
    {
        /** @var DList<int> $subject */
        $subject = new DList([1, 2, 3]);

        self::assertSame(4, $subject->max(fn(int $item) => 5 - $item));
    }

    #[Test]
    public function at(): void
    {
        /** @var DList<int> $subject */
        $subject = new DList([10, 20, 30]);

        self::assertSame(20, $subject->at(1));
    }
}
