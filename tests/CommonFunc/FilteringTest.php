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

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class FilteringTest extends TestCase
{
    #[Test]
    public function DList_filter(): void
    {
        $result = (new DList([1, 2, 3, 4]))
            ->filter(self::even(...))
        ;

        self::assertSame([2, 4], $result->getValuesArray());
    }

    #[Test]
    public function DList_filterNot(): void
    {
        $result = (new DList([1, 2, 3, 4]))
            ->filterNot(self::even(...))
        ;

        self::assertSame([1, 3], $result->getValuesArray());
    }

    #[Test]
    public function DSet_filter(): void
    {
        $result = (new DSet([1, 2, 3, 4]))
            ->filter(self::even(...))
        ;

        self::assertSame([2, 4], $result->getValuesArray());
    }

    #[Test]
    public function DSet_filterNot(): void
    {
        $result = (new DSet([1, 2, 3, 4]))
            ->filterNot(self::even(...))
        ;

        self::assertSame([1, 3], $result->getValuesArray());
    }

    #[Test]
    public function DMap_filter(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]))
            ->filter(static fn (string $key, int $value) => self::even($value))
        ;

        self::assertSame([2, 4], $result->getValuesArray());
        self::assertSame(['b', 'd'], $result->getKeysArray());
    }

    #[Test]
    public function DMap_filterNot(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]))
            ->filterNot(static fn (string $key, int $value) => self::even($value))
        ;

        self::assertSame([1, 3], $result->getValuesArray());
        self::assertSame(['a', 'c'], $result->getKeysArray());
    }

    #[Test]
    public function DMap_filterValues(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]))
            ->filterValues(self::even(...))
        ;

        self::assertSame([2, 4], $result->getValuesArray());
        self::assertSame(['b', 'd'], $result->getKeysArray());
    }

    #[Test]
    public function DMap_filterValuesNot(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]))
            ->filterValuesNot(self::even(...))
        ;

        self::assertSame([1, 3], $result->getValuesArray());
        self::assertSame(['a', 'c'], $result->getKeysArray());
    }

    #[Test]
    public function DMap_filterKeys(): void
    {
        $result = (new DMap([1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd']))
            ->filterKeys(self::even(...))
        ;

        self::assertSame(['b', 'd'], $result->getValuesArray());
        self::assertSame([2, 4], $result->getKeysArray());
    }

    #[Test]
    public function DMap_filterKeysNot(): void
    {
        $result = (new DMap([1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd']))
            ->filterKeysNot(self::even(...))
        ;

        self::assertSame(['a', 'c'], $result->getValuesArray());
        self::assertSame([1, 3], $result->getKeysArray());
    }

    private static function even(int $value): bool
    {
        return 0 === $value % 2;
    }
}
