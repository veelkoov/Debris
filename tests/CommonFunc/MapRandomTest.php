<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\Base\DIntMap;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\EveryMapTrait;
use Veelkoov\Debris\Maps\Pair;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[CoversClass(DIntMap::class)]
#[CoversTrait(EveryMapTrait::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class MapRandomTest extends TestCase
{
    #[Test]
    #[DataProvider('provideRandom_emptyCases')]
    public function random_empty(callable $test): void
    {
        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('The map is empty.');

        $test(new DMap());
    }

    public static function provideRandom_emptyCases(): iterable
    {
        return [
            [static fn (Map $map) => $map->random()],
            [static fn (Map $map) => $map->randomKey()],
            [static fn (Map $map) => $map->randomValue()],
        ];
    }

    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideRandomCases')]
    public function random(Map $subject, callable $extract): void
    {
        $firstResult = $extract($subject);
        for ($i = 0; $i < 1000; ++$i) {
            $nextResult = $extract($subject);
            if ($firstResult !== $nextResult) {
                break;
            }
        }

        self::assertNotSame($firstResult, $nextResult); // @phpstan-ignore variable.undefined (It will be assigned on first loop iteration)
    }

    public static function provideRandomCases(): iterable
    {
        $dMap = new DMap(self::getTestIntIntArray());

        $value = static fn (Map $map) => $map->random()->value;
        $key = static fn (Map $map) => $map->random()->key;
        $pairValue = static fn (Map $map) => $map->randomValue();
        $pairKey = static fn (Map $map) => $map->randomKey();

        return [
            [$dMap, $value],
            [$dMap, $key],
            [$dMap, $pairValue],
            [$dMap, $pairKey],
        ];
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
}
