<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\DMapKey;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;
use Veelkoov\Debris\StringSet;

/**
 * @internal
 */
#[CoversClass(StringSet::class)]
#[UsesClass(DMap::class)]
#[UsesClass(DMapKey::class)]
#[UsesClass(DMapKeyMapper::class)]
#[UsesClass(DSet::class)]
final class StringSetTest extends TestCase
{
    #[Test]
    public function join(): void
    {
        $subject = new StringSet(['abc', 'def']);

        self::assertSame('abc - def', $subject->join(' - '));
    }
}
