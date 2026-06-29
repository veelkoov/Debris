<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Sets;

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

/**
 * @internal
 */
#[CoversClass(StringSet::class)]
#[UsesClass(DMap::class)]
#[UsesClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKey::class)]
#[UsesClass(MapKeyMapper::class)]
final class StringSetTest extends TestCase
{
    #[Test]
    public function join(): void
    {
        $subject = new StringSet(['abc', 'def']);

        self::assertSame('abc - def', $subject->join(' - '));
    }
}
