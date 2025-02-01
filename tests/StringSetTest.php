<?php

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DScalarSet;
use Veelkoov\Debris\StringSet;

/**
 * @internal
 */
#[CoversClass(StringSet::class)]
#[UsesClass(DScalarSet::class)]
final class StringSetTest extends TestCase
{
    #[Test]
    public function join(): void
    {
        $subject = StringSet::of('abc', 'def');

        self::assertSame('abc - def', $subject->join(' - '));
    }
}
