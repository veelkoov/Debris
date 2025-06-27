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
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class EmptyAndSizeTest extends TestCase
{
    #[Test]
    public function DList_isEmpty(): void
    {
        self::assertTrue((new DList())->isEmpty());
        self::assertFalse((new DList([1]))->isEmpty());
    }

    #[Test]
    public function DSet_isEmpty(): void
    {
        self::assertTrue((new DSet())->isEmpty());
        self::assertFalse((new DSet([1]))->isEmpty());
    }

    #[Test]
    public function DMap_isEmpty(): void
    {
        self::assertTrue((new DMap())->isEmpty());
        self::assertFalse((new DMap([1 => 'a']))->isEmpty());
    }

    #[Test]
    public function DList_isNotEmpty(): void
    {
        self::assertFalse((new DList())->isNotEmpty());
        self::assertTrue((new DList([1]))->isNotEmpty());
    }

    #[Test]
    public function DSet_isNotEmpty(): void
    {
        self::assertFalse((new DSet())->isNotEmpty());
        self::assertTrue((new DSet([1]))->isNotEmpty());
    }

    #[Test]
    public function DMap_isNotEmpty(): void
    {
        self::assertFalse((new DMap())->isNotEmpty());
        self::assertTrue((new DMap([1 => 'a']))->isNotEmpty());
    }

    #[Test]
    public function DList_count(): void
    {
        self::assertCount(0, new DList());
        self::assertCount(2, new DList([1, 2]));
    }

    #[Test]
    public function DSet_count(): void
    {
        self::assertCount(0, new DSet());
        self::assertCount(2, new DSet([1, 2]));
    }

    #[Test]
    public function DMap_count(): void
    {
        self::assertCount(0, new DMap());
        self::assertCount(2, new DMap([1 => 'a', 2 => 'b']));
    }
}
