<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Maps\IntToString;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[CoversTrait(SimpleKeyMapTrait::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class MapEmptysAndCountTest extends TestCase
{
    #[Test]
    public function _isEmpty(): void
    {
        self::assertTrue((new DMap())->isEmpty());
        self::assertFalse((new DMap([1 => 'a']))->isEmpty());

        self::assertTrue((new IntToString())->isEmpty());
        self::assertFalse((new IntToString([1 => 'a']))->isEmpty());
    }

    #[Test]
    public function isNotEmpty(): void
    {
        self::assertFalse((new DMap())->isNotEmpty());
        self::assertTrue((new DMap([1 => 'a']))->isNotEmpty());

        self::assertFalse((new IntToString())->isNotEmpty());
        self::assertTrue((new IntToString([1 => 'a']))->isNotEmpty());
    }

    #[Test]
    public function _count(): void
    {
        self::assertCount(0, new DMap());
        self::assertCount(2, new DMap([1 => 'a', 2 => 'b']));

        self::assertCount(0, new IntToString());
        self::assertCount(2, new IntToString([1 => 'a', 2 => 'b']));
    }
}
