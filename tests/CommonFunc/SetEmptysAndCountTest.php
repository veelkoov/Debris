<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\IntSet;

/**
 * @internal
 */
#[CoversClass(DIntOrStringSet::class)]
#[CoversClass(DSet::class)]
#[UsesClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class SetEmptysAndCountTest extends TestCase
{
    #[Test]
    public function _isEmpty(): void
    {
        self::assertTrue((new DSet())->isEmpty());
        self::assertFalse((new DSet([1]))->isEmpty());

        self::assertTrue((new IntSet())->isEmpty());
        self::assertFalse((new IntSet([1]))->isEmpty());
    }

    #[Test]
    public function isNotEmpty(): void
    {
        self::assertFalse((new DSet())->isNotEmpty());
        self::assertTrue((new DSet([1]))->isNotEmpty());

        self::assertFalse((new IntSet())->isNotEmpty());
        self::assertTrue((new IntSet([1]))->isNotEmpty());
    }

    #[Test]
    public function _count(): void
    {
        self::assertCount(0, new DSet());
        self::assertCount(2, new DSet([1, 2]));

        self::assertCount(0, new IntSet());
        self::assertCount(2, new IntSet([1, 2]));
    }
}
