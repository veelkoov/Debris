<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DVec;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Pair;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class SortingTest extends TestCase
{
    /**
     * @var array<int, string>
     */
    private static array $input = [
        10 => 'A2',
        20 => 'b1',
        30 => 'c1',
        40 => 'B2',
        50 => 'a1',
        60 => 'C2',
    ];

    /**
     * @var array<int, string>
     */
    private static array $expectedPlain = [
        10 => 'A2',
        40 => 'B2',
        60 => 'C2',
        50 => 'a1',
        20 => 'b1',
        30 => 'c1',
    ];

    /**
     * @var array<int, string>
     */
    private static array $expectedComparator = [
        50 => 'a1',
        10 => 'A2',
        20 => 'b1',
        40 => 'B2',
        30 => 'c1',
        60 => 'C2',
    ];

    #[Test]
    public function DVec_sorted(): void
    {
        $subject = new DVec(array_values(self::$input));

        self::assertSame(
            array_values(self::$expectedPlain),
            $subject->sorted()->getValuesArray(),
        );

        self::assertSame(
            array_reverse(array_values(self::$expectedPlain)),
            $subject->sorted(reverse: true)->getValuesArray(),
        );

        self::assertSame(
            array_values(self::$expectedComparator),
            $subject->sorted(self::comparator(...))->getValuesArray(),
        );

        self::assertSame(
            array_reverse(array_values(self::$expectedComparator)),
            $subject->sorted(self::comparator(...), reverse: true)->getValuesArray(),
        );
    }

    #[Test]
    public function DSet_sorted(): void
    {
        $subject = new DSet(array_values(self::$input));

        self::assertSame(
            array_values(self::$expectedPlain),
            $subject->sorted()->getValuesArray(),
        );

        self::assertSame(
            array_reverse(array_values(self::$expectedPlain)),
            $subject->sorted(reverse: true)->getValuesArray(),
        );

        self::assertSame(
            array_values(self::$expectedComparator),
            $subject->sorted(self::comparator(...))->getValuesArray(),
        );

        self::assertSame(
            array_reverse(array_values(self::$expectedComparator)),
            $subject->sorted(self::comparator(...), reverse: true)->getValuesArray(),
        );
    }

    #[Test]
    public function DMap_sorted(): void
    {
        $subject = new DMap(self::$input);

        $result = $subject->sorted();
        self::assertSame(array_values(self::$expectedPlain), $result->getValuesArray());
        self::assertSame(array_keys(self::$expectedPlain), $result->getKeysArray());

        $result = $subject->sorted(reverse: true);
        self::assertSame(array_reverse(array_values(self::$expectedPlain)), $result->getValuesArray());
        self::assertSame(array_reverse(array_keys(self::$expectedPlain)), $result->getKeysArray());

        $result = $subject->sorted(self::comparator(...));
        self::assertSame(array_values(self::$expectedComparator), $result->getValuesArray());
        self::assertSame(array_keys(self::$expectedComparator), $result->getKeysArray());

        $result = $subject->sorted(self::comparator(...), reverse: true);
        self::assertSame(array_reverse(array_values(self::$expectedComparator)), $result->getValuesArray());
        self::assertSame(array_reverse(array_keys(self::$expectedComparator)), $result->getKeysArray());
    }

    /**
     * @param Pair<int, string>|string $a
     * @param Pair<int, string>|string $b
     */
    private static function comparator(Pair|string $a, Pair|string $b): int
    {
        return strtolower(\is_string($a) ? $a : $a->value) <=> strtolower(\is_string($b) ? $b : $b->value);
    }
}
