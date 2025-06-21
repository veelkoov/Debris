<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Base\Internal\Pair;
use Veelkoov\Debris\IntList;
use Veelkoov\Debris\IntSet;
use Veelkoov\Debris\StringIntMap;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class MapTest extends TestCase
{
    #[Test]
    public function DList_map(): void
    {
        $result = (new DList([1, 2, 3]))
            ->map(static fn (int $value) => $value * 2)
        ;

        self::assertSame([2, 4, 6], $result->getValuesArray());
    }

    #[Test]
    public function DList_mapInto(): void
    {
        $target = new IntList();

        $result = (new DList([1, 2, 3]))
            ->mapInto(static fn (int $value) => $value * 2, $target)
        ;

        self::assertSame($target, $result);
        self::assertSame([2, 4, 6], $result->getValuesArray());
    }

    #[Test]
    public function DSet_map(): void
    {
        // Making sure 5 * 2 % 10 will be deduplicated with 0
        $result = (new DSet([0, 1, 2, 5]))
            ->map(static fn (int $value) => $value * 2 % 10)
        ;

        self::assertSame([0, 2, 4], $result->getValuesArray());
    }

    #[Test]
    public function DSet_mapInto(): void
    {
        $target = new IntSet();

        // Making sure 5 * 2 % 10 will be deduplicated with 0
        $result = (new DSet([0, 1, 2, 5]))
            ->mapInto(static fn (int $value) => $value * 2 % 10, $target)
        ;

        self::assertSame($target, $result);
        self::assertSame([0, 2, 4], $result->getValuesArray());
    }

    #[Test]
    public function DMap_map(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->map(static fn (string $key, int $value) => [$key.$key, $value * 2])
        ;

        self::assertSame(['aa', 'bb', 'cc'], $result->getKeysArray());
        self::assertSame([2, 4, 6], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapInto(): void
    {
        $target = new StringIntMap();

        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->mapInto(static fn (string $key, int $value) => [$key.$key, $value * 2], $target)
        ;

        self::assertSame($target, $result);
        self::assertSame(['aa', 'bb', 'cc'], $result->getKeysArray());
        self::assertSame([2, 4, 6], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapKeys(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->mapKeys(static fn (string $key) => $key.$key)
        ;

        self::assertSame(['aa', 'bb', 'cc'], $result->getKeysArray());
        self::assertSame([1, 2, 3], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapKeysInto(): void
    {
        $target = new StringIntMap();

        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->mapKeysInto(static fn (string $key) => $key.$key, $target)
        ;

        self::assertSame($target, $result);
        self::assertSame(['aa', 'bb', 'cc'], $result->getKeysArray());
        self::assertSame([1, 2, 3], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapValues(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->mapValues(static fn (int $value) => $value * 2)
        ;

        self::assertSame(['a', 'b', 'c'], $result->getKeysArray());
        self::assertSame([2, 4, 6], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapValuesInto(): void
    {
        $target = new StringIntMap();

        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->mapValuesInto(static fn (int $value) => $value * 2, $target)
        ;

        self::assertSame($target, $result);
        self::assertSame(['a', 'b', 'c'], $result->getKeysArray());
        self::assertSame([2, 4, 6], $result->getValuesArray());
    }
}
