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
use Veelkoov\Debris\Maps\Base\DIntMap;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Maps\IntToString;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @internal
 */
#[CoversClass(DIntMap::class)]
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[CoversTrait(SimpleKeyMapTrait::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(DIntOrStringSet::class)]
final class SliceTest extends TestCase
{
    #[Test]
    public function DVec_slice(): void
    {
        $subject = new DVec([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

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
        $subject = new IntToString([1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f', 7 => 'g', 8 => 'h', 9 => 'i', 10 => 'j']);

        self::assertSame([3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f'], $subject->slice(2, 4)->toArray());
        self::assertSame([6 => 'f', 7 => 'g', 8 => 'h'], $subject->slice(-5, -2)->toArray());
    }
}
