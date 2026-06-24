<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\NoSingleElementException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Maps\IntToInt;
use Veelkoov\Debris\Maps\Pair;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[CoversTrait(SimpleKeyMapTrait::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class MapSingleTest extends TestCase
{
    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideSingle_exceptionsCases')]
    public function single_exceptions(Map $subject, callable $test, string $expectedMessage): void
    {
        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage($expectedMessage);

        $test($subject);
    }

    public static function provideSingle_exceptionsCases(): iterable
    {
        $empty = [];
        $twoItems = [1 => 1, 2 => 2];

        $getSingle = static fn (Map $map) => $map->single();
        $getSingleKey = static fn (Map $map) => $map->singleKey();
        $getSingleValue = static fn (Map $map) => $map->singleValue();

        $zeroErrMsg = 'The map has 0 items instead of exactly one.';
        $twoErrMsg = 'The map has 2 items instead of exactly one.';

        return [
            [new DMap($empty), $getSingle, $zeroErrMsg],
            [new IntToInt($empty), $getSingle, $zeroErrMsg],
            [new DMap($twoItems), $getSingle, $twoErrMsg],
            [new IntToInt($twoItems), $getSingle, $twoErrMsg],

            [new DMap($empty), $getSingleKey, $zeroErrMsg],
            [new IntToInt($empty), $getSingleKey, $zeroErrMsg],
            [new DMap($twoItems), $getSingleKey, $twoErrMsg],
            [new IntToInt($twoItems), $getSingleKey, $twoErrMsg],

            [new DMap($empty), $getSingleValue, $zeroErrMsg],
            [new IntToInt($empty), $getSingleValue, $zeroErrMsg],
            [new DMap($twoItems), $getSingleValue, $twoErrMsg],
            [new IntToInt($twoItems), $getSingleValue, $twoErrMsg],
        ];
    }

    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideSingle_worksCases')]
    public function single_works(Map $subject, callable $test, mixed $expected): void
    {
        self::assertSame($expected, $test($subject));
    }

    public static function provideSingle_worksCases(): iterable
    {
        return [
            [new DMap([10 => 1000]), static fn (Map $map) => $map->single()->key, 10],
            [new DMap([20 => 2000]), static fn (Map $map) => $map->singleKey(), 20],

            [new DMap([30 => 3000]), static fn (Map $map) => $map->single()->value, 3000],
            [new DMap([40 => 4000]), static fn (Map $map) => $map->singleValue(), 4000],

            [new IntToInt([50 => 5000]), static fn (Map $map) => $map->single()->key, 50],
            [new IntToInt([60 => 6000]), static fn (Map $map) => $map->singleKey(), 60],

            [new IntToInt([70 => 7000]), static fn (Map $map) => $map->single()->value, 7000],
            [new IntToInt([80 => 8000]), static fn (Map $map) => $map->singleValue(), 8000],
        ];
    }
}
