<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DIntMap;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DIntMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class DIntMapTest extends TestCase
{
    #[Test]
    public function toArray(): void
    {
        $subject = new DIntMap([10 => 'a', 30 => 'c']);

        self::assertSame([10 => 'a', 30 => 'c'], $subject->toArray());
    }
}
