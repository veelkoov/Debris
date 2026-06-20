<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DVec;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class EmptyAndSizeTest extends TestCase
{
    #[Test]
    public function DVec_isEmpty(): void
    {
        self::assertTrue((new DVec())->isEmpty());
        self::assertFalse((new DVec([1]))->isEmpty());
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
    public function DVec_isNotEmpty(): void
    {
        self::assertFalse((new DVec())->isNotEmpty());
        self::assertTrue((new DVec([1]))->isNotEmpty());
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
    public function DVec_count(): void
    {
        self::assertCount(0, new DVec());
        self::assertCount(2, new DVec([1, 2]));
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
