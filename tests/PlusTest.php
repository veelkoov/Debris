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
final class PlusTest extends TestCase
{
    #[Test]
    public function DList_plus(): void
    {
        $subject = new DList([1]);

        $result = $subject->plus(2)->plus(1);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame([1], $subject->toArray(), 'Original object should remain unchanged');
        self::assertSame([1, 2, 1], $result->toArray());
    }

    #[Test]
    public function DSet_plus(): void
    {
        $subject = new DSet([1]);

        $result = $subject->plus(2)->plus(1);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame([1], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame([1, 2], $result->getValuesArray());
    }

    #[Test]
    public function DMap_plus(): void
    {
        $subject = new DMap(['a' => 1]);

        $result = $subject->plus('b', 2)->plus('a', 3);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame(['a'], $subject->getKeysArray(), 'Original object should remain unchanged');
        self::assertSame([1], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame(['a', 'b'], $result->getKeysArray());
        self::assertSame([3, 2], $result->getValuesArray());
    }
}
