<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DScalarMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\IntIntMap;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(DScalarMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class ShuffleTest extends TestCase
{
    #[Test]
    public function DList_shuffle(): void
    {
        $input = self::getTestList();
        $subject = (new DList($input))->shuffle();

        self::assertIntListIsShuffledOriginal($input, $subject->getValuesArray());
    }

    #[Test]
    public function DSet_shuffle(): void
    {
        $input = self::getTestList();
        $subject = (new DSet($input))->shuffle();

        self::assertIntListIsShuffledOriginal($input, $subject->getValuesArray());
    }

    #[Test]
    public function DMap_shuffle(): void
    {
        $input = self::getTestIntIntArray();
        $subject = (new IntIntMap($input))->shuffle();

        self::assertIntIntArrayIsShuffledOriginal($input, $subject->toArray());
    }

    /**
     * @return list<int>
     */
    private static function getTestList(): array
    {
        $input = range(1, 100);

        self::assertSame(5, $input[5 - 1]);
        self::assertSame(95, $input[95 - 1]);

        return $input;
    }

    /**
     * @return array<int, int>
     */
    private static function getTestIntIntArray(): array
    {
        $keys = range(1, 100);
        $values = range(50001, 50100);
        $input = array_combine($keys, $values);

        self::assertSame(50005, $input[5]);
        self::assertSame(50095, $input[95]);

        return $input;
    }

    /**
     * @param list<int> $original
     * @param list<int> $checked
     */
    private static function assertIntListIsShuffledOriginal(array $original, array $checked): void
    {
        $sorted = $checked;
        sort($sorted);
        self::assertSame($original, $sorted);

        self::assertNotSame($original, $checked);
    }

    /**
     * @param array<int, int> $original
     * @param array<int, int> $checked
     */
    private static function assertIntIntArrayIsShuffledOriginal(array $original, array $checked): void
    {
        $sorted = $checked;
        asort($sorted);
        self::assertSame($original, $sorted);

        self::assertNotSame($original, $checked);
    }
}
