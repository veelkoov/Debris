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
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(DMapKeyMapper::class)]
final class AddSetTest extends TestCase
{
    #[Test]
    public function DList_add(): void
    {
        $subject = DList::mut([1]);
        $subject->add(2);
        $subject->add(1);

        self::assertSame([1, 2, 1], $subject->toArray());
    }

    #[Test]
    public function DSet_add(): void
    {
        $subject = new DSet([1]);
        $subject->add(2);
        $subject->add(1);

        self::assertSame([1, 2], $subject->getValuesArray());
    }

    #[Test]
    public function DMap_set(): void
    {
        $subject = new DMap(['a' => 1]);
        $subject->set('b', 2);
        $subject->set('a', 3);

        self::assertSame(['a', 'b'], $subject->getKeysArray());
        self::assertSame([3, 2], $subject->getValuesArray());
    }

    #[Test]
    public function DList_addAll(): void
    {
        $subject = DList::mut([1]);
        $subject->addAll([2, 1]);

        self::assertSame([1, 2, 1], $subject->toArray());
    }

    #[Test]
    public function DSet_addAll(): void
    {
        $subject = new DSet([1]);
        $subject->addAll([2, 1]);

        self::assertSame([1, 2], $subject->getValuesArray());
    }

    #[Test]
    public function DMap_setAll(): void
    {
        $subject = new DMap(['a' => 1]);
        $subject->setAll(['b' => 2, 'a' => 3]);

        self::assertSame(['a', 'b'], $subject->getKeysArray());
        self::assertSame([3, 2], $subject->getValuesArray());
    }
}
