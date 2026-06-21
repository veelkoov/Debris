<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\ChangingImmutableException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Pair;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class MinusTest extends TestCase
{
    #[Test]
    public function DVec_minus(): void
    {
        $subject = new DVec([1, 2, 2, 3, 2, 4]);

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
    public function DMap_minusValue(): void
    {
        $subject = new DMap(['a' => 1, 'b' => 2, 'c' => 2, 'd' => 4]);

        $result = $subject->minusValue(2)->minusValue(4)->minusValue(5);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame(['a', 'b', 'c', 'd'], $subject->getKeysArray(), 'Original object should remain unchanged');
        self::assertSame([1, 2, 2, 4], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame(['a', 'c'], $result->getKeysArray());
        self::assertSame([1, 2], $result->getValuesArray());
    }

    #[Test]
    public function DMap_minusKey(): void
    {
        $subject = new DMap(['a' => 1, 'b' => 2, 'c' => 2, 'd' => 4]);

        $result = $subject->minusKey('b')->minusKey('d')->minusKey('e');

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame(['a', 'b', 'c', 'd'], $subject->getKeysArray(), 'Original object should remain unchanged');
        self::assertSame([1, 2, 2, 4], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame(['a', 'c'], $result->getKeysArray());
        self::assertSame([1, 2], $result->getValuesArray());
    }
}
