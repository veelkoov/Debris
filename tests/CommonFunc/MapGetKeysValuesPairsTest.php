<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\Base\DIntMap;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\DStringMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Maps\IntToString;
use Veelkoov\Debris\Maps\Pair;
use Veelkoov\Debris\Maps\StringToInt;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\Base\EverySetTrait;
use Veelkoov\Debris\Sets\IntSet;
use Veelkoov\Debris\Sets\StringSet;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @internal
 */
#[CoversClass(DIntMap::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DStringMap::class)]
#[CoversTrait(SimpleKeyMapTrait::class)]
#[UsesClass(DIntOrStringSet::class)]
#[UsesClass(DSet::class)]
#[UsesClass(DVec::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
#[UsesTrait(EverySetTrait::class)]
final class MapGetKeysValuesPairsTest extends TestCase
{
    // @phpstan-ignore missingType.iterableValue,missingType.generics
    #[Test]
    #[DataProvider('provide_getCases')]
    public function _get(Map $subject, callable $test, ?string $class, array $expected): void
    {
        $result = $test($subject);

        if (null !== $class) {
            self::assertInstanceOf($class, $result); // @phpstan-ignore argument.type
        }

        self::assertEqualsCanonicalizing($expected, [...$result]); // @phpstan-ignore arrayUnpacking.nonIterable
    }

    public static function provide_getCases(): iterable
    {
        $getKeys = static fn (Map $subject) => $subject->getKeys();
        $getValues = static fn (Map $subject) => $subject->getValues();
        $getPairsArray = static fn (Map $subject) => $subject->getPairsArray();

        return [
            [new DMap(['a' => 1, 'b' => 2, 'c' => 3]), $getKeys, DSet::class, ['a', 'b', 'c']],
            [new DMap(['a' => 1, 'b' => 2, 'c' => 3]), $getValues, DVec::class, [1, 2, 3]],
            [new DMap(['a' => 1, 'b' => 2, 'c' => 3]), $getPairsArray, null,
                [new Pair('a', 1), new Pair('b', 2), new Pair('c', 3)]],

            [new StringToInt(['a' => 1, 'b' => 2, 'c' => 3]), $getKeys, StringSet::class, ['a', 'b', 'c']],
            [new StringToInt(['a' => 1, 'b' => 2, 'c' => 3]), $getValues, DVec::class, [1, 2, 3]],
            [new StringToInt(['a' => 1, 'b' => 2, 'c' => 3]), $getPairsArray, null,
                [new Pair('a', 1), new Pair('b', 2), new Pair('c', 3)]],

            [new IntToString([1 => 'a', 2 => 'b', 3 => 'c']), $getKeys, IntSet::class, [1, 2, 3]],
            [new IntToString([1 => 'a', 2 => 'b', 3 => 'c']), $getValues, DVec::class, ['a', 'b', 'c']],
            [new IntToString([1 => 'a', 2 => 'b', 3 => 'c']), $getPairsArray, null,
                [new Pair(1, 'a'), new Pair(2, 'b'), new Pair(3, 'c')]],
        ];
    }
}
