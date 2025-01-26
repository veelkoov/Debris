<?php

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\DScalarSet;
use Veelkoov\Debris\StringSet;

#[CoversClass(StringSet::class)]
#[UsesClass(DScalarSet::class)]
class StringSetTest extends TestCase
{
    #[Test]
    public function join(): void
    {
        $subject = StringSet::of('abc', 'def');

        self::assertSame('abc - def', $subject->join(' - '));
    }
}
