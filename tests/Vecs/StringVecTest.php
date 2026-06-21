<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Vecs;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKey;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\StringSet;
use Veelkoov\Debris\Vecs\Base\DVec;
use Veelkoov\Debris\Vecs\StringVec;

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
