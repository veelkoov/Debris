<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DSet::class)]
#[CoversClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class IntersectTest extends TestCase
{
    #[Test]
    public function DList_intersectWithArray(): void
    {
        $subject = new DList([10, 20, 30, 40, 50]);

        $result = $subject->intersect([-10, 10, 30, 50, 70]);

        self::assertSame([10, 30, 50], $result->getValuesArray());
    }

    #[Test]
    public function DList_intersectWithOther(): void
    {
        $subject = new DList([10, 20, 30, 40, 50]);

        $result = $subject->intersect(new DList([-10, 10, 30, 50, 70]));

        self::assertSame([10, 30, 50], $result->getValuesArray());
    }

    #[Test]
    public function DSet_intersectWithArray(): void
    {
        $subject = new DSet([10, 20, 30, 40, 50]);

        $result = $subject->intersect([-10, 10, 30, 50, 70]);

        self::assertSame([10, 30, 50], $result->getValuesArray());
    }

    #[Test]
    public function DSet_intersectWithOther(): void
    {
        $subject = new DSet([10, 20, 30, 40, 50]);

        $result = $subject->intersect(new DSet([-10, 10, 30, 50, 70]));

        self::assertSame([10, 30, 50], $result->getValuesArray());
    }

    // TODO: DMap
}
