<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\AnyToNullString;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\StringToBool;
use Veelkoov\Debris\Maps\StringToNullString;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class MapIterationTest extends TestCase
{
    // @phpstan-ignore missingType.iterableValue,missingType.iterableValue,missingType.generics
    #[Test]
    #[DataProvider('provideIterationCases')]
    public function iteration(Map $subject, array $expectedKeys, array $expectedValues): void
    {
        $resultValues = [];
        $resultKeys = [];

        foreach ($subject as $key => $value) {
            $resultKeys[] = $key;
            $resultValues[] = $value;
        }

        self::assertSame($expectedValues, $resultValues);
        self::assertSame($expectedKeys, $resultKeys);
    }

    public static function provideIterationCases(): iterable
    {
        $keys1 = [-1, new \stdClass(), 0.45, 'xyz'];
        $values1 = ['abc', 1, new \stdClass(), false];
        $subject1 = new DMap();
        foreach ($keys1 as $index => $key) {
            $subject1->set($key, $values1[$index]);
        }

        $keys2 = ['', 0, false, 0.0];
        $values2 = ['AAA', null, 'CCC', null];
        $subject2 = new AnyToNullString();
        foreach ($keys2 as $index => $key) {
            $subject2->set($key, $values2[$index]);
        }

        return [
            [$subject1, $keys1, $values1],
            [$subject2, $keys2, $values2],

            // Regression test
            [new StringToBool(['a' => true, 'b' => false, 'c' => true]), ['a', 'b', 'c'], [true, false, true]],

            [
                new StringToNullString(['a' => 'AAA', 'b' => null, 'c' => null, 'd' => 'DDD']),
                ['a', 'b', 'c', 'd'],
                ['AAA', null, null, 'DDD'],
            ],
        ];
    }
}
