<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DScalarSet;
use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\StringSet;

/**
 * @internal
 */
#[CoversClass(DStringMap::class)]
#[UsesClass(DScalarSet::class)]
final class DStringMapTest extends TestCase
{
    #[Test]
    public function getKeys(): void
    {
        $subject = new DStringMap([
            'a' => 0,
            'b' => 1,
            'c' => 2,
        ]);

        $result = $subject->getKeys();
        self::assertInstanceOf(StringSet::class, $result); // @phpstan-ignore staticMethod.alreadyNarrowedType (Checking contract)
        self::assertEqualsCanonicalizing(['a', 'b', 'c'], $result->toArray());
    }
}
