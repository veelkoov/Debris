<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(DMapKeyMapper::class)]
final class FilteringTest extends TestCase
{
    #[Test]
    public function DList_filter(): void
    {
        $result = (new DList([1, 2, 3, 4]))
            ->filter(self::even(...))
        ;

        self::assertSame([2, 4], $result->toArray());
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
    public function DMap_filter(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]))
            ->filter(static fn (string $key, int $value) => self::even($value))
        ;

        self::assertSame([2, 4], $result->getValuesArray());
        self::assertSame(['b', 'd'], $result->getKeysArray());
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

    private static function even(int $value): bool
    {
        return 0 === $value % 2;
    }
}
