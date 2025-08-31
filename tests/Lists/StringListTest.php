<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Lists;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKey;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Lists\StringList;
use Veelkoov\Debris\Sets\StringSet;

/**
 * @internal
 */
#[CoversClass(StringList::class)]
#[UsesClass(DList::class)]
#[UsesClass(DMap::class)]
#[UsesClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKey::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(StringSet::class)]
final class StringListTest extends TestCase
{
    #[Test]
    public function join(): void
    {
        $subject = StringList::of('abc', 'def');

        self::assertSame('abc - def', $subject->join(' - '));
    }

    #[Test]
    public function split_empty(): void
    {
        self::assertSame([], StringList::split(',', '')->getValuesArray());
    }

    #[Test]
    public function split_nonEmpty(): void
    {
        self::assertSame(['aaa', 'bbb'], StringList::split(', ', 'aaa, bbb')->getValuesArray());
    }

    #[Test]
    public function toSet(): void
    {
        $subject = StringList::of('abc', 'def', 'abc');

        $result = $subject->toSet();

        self::assertEqualsCanonicalizing(['abc', 'def'], $result->getValuesArray());
    }
}
