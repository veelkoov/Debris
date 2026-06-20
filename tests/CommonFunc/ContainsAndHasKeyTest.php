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

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class ContainsAndHasKeyTest extends TestCase
{
    #[Test]
    public function DVec_contains(): void
    {
        $subject = new DVec([1, 2, 3]);
        self::assertTrue($subject->contains(3));
        self::assertFalse($subject->contains(4));
    }

    #[Test]
    public function DSet_contains(): void
    {
        $subject = new DSet([1, 2, 3]);
        self::assertTrue($subject->contains(3));
        self::assertFalse($subject->contains(4));
    }

    #[Test]
    public function DMap_contains(): void
    {
        $subject = new DMap(['a' => 1, 'b' => 2, 'c' => 3]);
        self::assertTrue($subject->contains(3));
        self::assertFalse($subject->contains(4));
    }

    #[Test]
    public function DMap_hasKey(): void
    {
        $subject = new DMap(['a' => 1, 'b' => 2, 'c' => 3]);
        self::assertTrue($subject->hasKey('c'));
        self::assertFalse($subject->hasKey('d'));
    }
}
