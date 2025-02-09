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
use Veelkoov\Debris\Base\Internal\DPair;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(DMapKeyMapper::class)]
#[UsesClass(DPair::class)]
final class MapTest extends TestCase
{
    #[Test]
    public function DList_map(): void
    {
        $result = (new DList([1, 2, 3]))
            ->map(static fn (int $value) => $value * 2)
        ;

        self::assertSame([2, 4, 6], $result->toArray());
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
    public function DMap_map(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->map(static fn (string $key, int $value) => new DPair($key, $value * 2))
        ;

        self::assertSame(['a', 'b', 'c'], $result->getKeysArray());
        self::assertSame([2, 4, 6], $result->getValuesArray());
    }
}
