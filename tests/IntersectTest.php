<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\Internal\Freezer;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[UsesClass(Freezer::class)]
final class IntersectTest extends TestCase
{
    #[Test]
    public function DList_intersectWithArray(): void
    {
        $subject = new DList([10, 20, 30, 40, 50]);

        $result = $subject->intersect([-10, 10, 30, 50, 70]);

        self::assertSame([10, 30, 50], $result->toArray());
    }

    #[Test]
    public function DList_intersectWithOther(): void
    {
        $subject = new DList([10, 20, 30, 40, 50]);

        $result = $subject->intersect(new DList([-10, 10, 30, 50, 70]));

        self::assertSame([10, 30, 50], $result->toArray());
    }

    // TODO: DSet & DMap
}
