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
use Veelkoov\Debris\Exception\ChangingImmutableException;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class MinusTest extends TestCase
{
    #[Test]
    public function DList_minus(): void
    {
        $subject = new DList([1, 2, 2, 3, 2, 4]);

        $result = $subject->minus(2)->minus(2)->minus(4)->minus(5);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame([1, 2, 2, 3, 2, 4], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame([1, 3, 2], $result->getValuesArray());
    }

    #[Test]
    public function DSet_minus(): void
    {
        $subject = new DSet([1, 2, 3]);

        $result = $subject->minus(2)->minus(3)->minus(3)->minus(4);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame([1, 2, 3], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame([1], $result->getValuesArray());
    }

    #[Test]
    public function DMap_minus(): void
    {
        $subject = new DMap(['a' => 1, 'b' => 2, 'c' => 2, 'd' => 4]);

        $result = $subject->minus(2)->minus(4)->minus(5);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame(['a', 'b', 'c', 'd'], $subject->getKeysArray(), 'Original object should remain unchanged');
        self::assertSame([1, 2, 2, 4], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame(['a', 'c'], $result->getKeysArray());
        self::assertSame([1, 2], $result->getValuesArray());
    }

    // TODO: There is no method for substracting key(s)
}
