<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Base\Internal\Pair;
use Veelkoov\Debris\IntStringMap;

/**
 * @internal
 */
#[CoversClass(IntStringMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class IntStringMapTest extends TestCase
{
    #[Test]
    public function fromRows_blocksWrongKeyType(): void
    {
        $this->expectNotToPerformAssertions();

        try {
            IntStringMap::fromRows(
                [['key' => 'a', 'value' => 'a']],
                'key',
                'value',
            );

            self::fail();
        } catch (\TypeError) {
            // Correct behavior
        }
    }

    #[Test]
    public function fromRows_blocksWrongValueType(): void
    {
        $this->expectNotToPerformAssertions();

        try {
            IntStringMap::fromRows(
                [['key' => 1, 'value' => 1]],
                'key',
                'value',
            );

            self::fail();
        } catch (\TypeError) {
            // Correct behavior
        }
    }
}
