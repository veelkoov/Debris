<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKey;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\DStringMap;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\StringSet;

/**
 * @internal
 */
#[CoversClass(DStringMap::class)]
#[UsesClass(DMap::class)]
#[UsesClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKey::class)]
#[UsesClass(MapKeyMapper::class)]
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
        self::assertEqualsCanonicalizing(['a', 'b', 'c'], $result->getValuesArray());
    }
}
