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
use Veelkoov\Debris\StringIntMap;

/**
 * @internal
 */
#[CoversClass(StringIntMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class StringIntMapTest extends TestCase
{
    #[Test]
    public function sorted_normal(): void
    {
        $input = ['a' => 1, 'c' => 3, 'b' => 2];
        $expected = ['a' => 1, 'b' => 2, 'c' => 3];

        $subject = new StringIntMap($input);
        $result = $subject->sorted()->toArray();

        self::assertSame($expected, $result);
    }

    #[Test]
    public function sorted_reversed(): void
    {
        $input = ['a' => 1, 'c' => 3, 'b' => 2];
        $expected = ['c' => 3, 'b' => 2, 'a' => 1];

        $subject = new StringIntMap($input);
        $result = $subject->sorted(reverse: true)->toArray();

        self::assertSame($expected, $result);
    }

    #[Test]
    public function sorted_withComparator(): void
    {
        $input = ['a' => 10, 'b' => 8, 'c' => 14];
        $expected = ['a' => 10, 'c' => 14, 'b' => 8];

        $subject = new StringIntMap($input);
        $result = $subject->sorted(
            static fn (Pair $i1, Pair $i2) => $i1->value % 10 - $i2->value % 10
        )->toArray();

        self::assertSame($expected, $result);
    }

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
