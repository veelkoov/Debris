<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Vecs;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DVec;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKey;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Vecs\StringVec;
use Veelkoov\Debris\Sets\StringSet;

/**
 * @internal
 */
#[CoversClass(StringVec::class)]
#[UsesClass(DVec::class)]
#[UsesClass(DMap::class)]
#[UsesClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKey::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(StringSet::class)]
final class StringVecTest extends TestCase
{
    #[Test]
    public function join(): void
    {
        $subject = StringVec::of('abc', 'def');

        self::assertSame('abc - def', $subject->join(' - '));
    }

    #[Test]
    public function split_empty(): void
    {
        self::assertSame([], StringVec::split(',', '')->getValuesArray());
    }

    #[Test]
    public function split_nonEmpty(): void
    {
        self::assertSame(['aaa', 'bbb'], StringVec::split(', ', 'aaa, bbb')->getValuesArray());
    }

    #[Test]
    public function toSet(): void
    {
        $subject = StringVec::of('abc', 'def', 'abc');

        $result = $subject->toSet();

        self::assertEqualsCanonicalizing(['abc', 'def'], $result->getValuesArray());
    }
}
