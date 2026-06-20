<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\DVec;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Pair;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class PlusAllTest extends TestCase
{
    #[Test]
    public function DVec_plusAll(): void
    {
        $subject = new DVec([1]);

        $result = $subject->plusAll([2, 1]);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame([1], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame([1, 2, 1], $result->getValuesArray());
    }

    #[Test]
    public function DSet_plusAll(): void
    {
        $subject = new DSet([1]);

        $result = $subject->plusAll([2, 1]);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame([1], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame([1, 2], $result->getValuesArray());
    }

    #[Test]
    public function DMap_plusAll(): void
    {
        $subject = new DMap(['a' => 1]);

        $result = $subject->plusAll(['b' => 2, 'a' => 3]);

        self::assertNotSame($subject, $result, 'Result should be a new, different instance');
        self::assertSame(['a'], $subject->getKeysArray(), 'Original object should remain unchanged');
        self::assertSame([1], $subject->getValuesArray(), 'Original object should remain unchanged');
        self::assertSame(['a', 'b'], $result->getKeysArray());
        self::assertSame([3, 2], $result->getValuesArray());
    }
}
