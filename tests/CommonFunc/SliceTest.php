<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DScalarMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\IntStringMap;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(DScalarMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class SliceTest extends TestCase
{
    #[Test]
    public function DList_slice(): void
    {
        $subject = new DList([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        self::assertSame([3, 4, 5, 6], $subject->slice(2, 4)->getValuesArray());
        self::assertSame([6, 7, 8], $subject->slice(-5, -2)->getValuesArray());
    }

    #[Test]
    public function DSet_slice(): void
    {
        $subject = new DSet([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        self::assertSame([3, 4, 5, 6], $subject->slice(2, 4)->getValuesArray());
        self::assertSame([6, 7, 8], $subject->slice(-5, -2)->getValuesArray());
    }

    #[Test]
    public function DMap_slice(): void
    {
        $subject = new IntStringMap([1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f', 7 => 'g', 8 => 'h', 9 => 'i', 10 => 'j']);

        self::assertSame([3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f'], $subject->slice(2, 4)->toArray());
        self::assertSame([6 => 'f', 7 => 'g', 8 => 'h'], $subject->slice(-5, -2)->toArray());
    }
}
