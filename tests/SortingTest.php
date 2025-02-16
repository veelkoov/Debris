<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Base\Internal\Pair;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class SortingTest extends TestCase
{
    #[Test]
    public function DList_sorted(): void
    {
        $subject = new DList([10, 50, 20, 40, 30]);

        $comparator = static fn (int $i1, int $i2) => $i1 <=> $i2;

        self::assertSame([10, 20, 30, 40, 50], $subject->sorted($comparator)->toArray());
        self::assertSame([50, 40, 30, 20, 10], $subject->sorted($comparator, reverse: true)->toArray());
    }

    #[Test]
    public function DSet_sorted(): void
    {
        $subject = new DSet([10, 50, 20, 40, 30]);

        $comparator = static fn (int $i1, int $i2) => $i1 <=> $i2;

        self::assertSame([10, 20, 30, 40, 50], $subject->sorted($comparator)->getValuesArray());
        self::assertSame([50, 40, 30, 20, 10], $subject->sorted($comparator, reverse: true)->getValuesArray());
    }

    #[Test]
    public function DMap_sorted(): void
    {
        $subject = new DMap(['a' => 10, 'b' => 50, 'c' => 20, 'd' => 40, 'e' => 30]);

        $comparator = static fn (Pair $i1, Pair $i2) => $i1->value <=> $i2->value;

        $result = $subject->sorted($comparator);
        self::assertSame([10, 20, 30, 40, 50], $result->getValuesArray());
        self::assertSame(['a', 'c', 'e', 'd', 'b'], $result->getKeysArray());

        $result = $subject->sorted($comparator, reverse: true);
        self::assertSame([50, 40, 30, 20, 10], $result->getValuesArray());
        self::assertSame(['b', 'd', 'e', 'c', 'a'], $result->getKeysArray());
    }
}
