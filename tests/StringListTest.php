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
use Veelkoov\Debris\Base\Internal\DMapKey;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;
use Veelkoov\Debris\StringList;
use Veelkoov\Debris\StringSet;

/**
 * @internal
 */
#[CoversClass(StringList::class)]
#[UsesClass(DList::class)]
#[UsesClass(DMap::class)]
#[UsesClass(DMapKey::class)]
#[UsesClass(DMapKeyMapper::class)]
#[UsesClass(DSet::class)]
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
    public function toSet(): void
    {
        $subject = StringList::of('abc', 'def', 'abc');

        $result = $subject->toSet();

        self::assertEqualsCanonicalizing(['abc', 'def'], $result->getValuesArray());
    }
}
