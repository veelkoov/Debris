<?php

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\DList;
use Veelkoov\Debris\DScalarSet;
use Veelkoov\Debris\StringList;
use Veelkoov\Debris\StringSet;

#[CoversClass(StringList::class)]
#[UsesClass(DList::class)]
#[UsesClass(DScalarSet::class)]
#[UsesClass(StringSet::class)]
class StringListTest extends TestCase
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

        self::assertEqualsCanonicalizing(['abc', 'def'], $result->toArray());
    }
}
