<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;
use Veelkoov\Debris\StringIntMap;

/**
 * @internal
 */
#[CoversClass(StringIntMap::class)]
#[UsesClass(DMapKeyMapper::class)]
final class StringIntMapTest extends TestCase
{
    #[Test]
    public function fromRows_blocksWrongKeyType(): void
    {
        $this->expectNotToPerformAssertions();

        try {
            StringIntMap::fromRows(
                [['key' => 1, 'value' => 1]],
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
            StringIntMap::fromRows(
                [['key' => 'a', 'value' => 'a']],
                'key',
                'value',
            );

            self::fail();
        } catch (\TypeError) {
            // Correct behavior
        }
    }
}
