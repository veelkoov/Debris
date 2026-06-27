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
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Set;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\IntSet;

/**
 * @internal
 */
#[CoversClass(DSet::class)]
#[CoversClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class SetIterationTest extends TestCase
{
    // @phpstan-ignore missingType.iterableValue,missingType.iterableValue,missingType.generics
    #[Test]
    #[DataProvider('provideIterationCases')]
    public function iteration(Set $subject, array $expectedKeys, array $expectedValues): void
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
        $values = ['a', null, 1, new \stdClass(), false, 0, 0.0, true];

        return [
            [new DSet($values), [0, 1, 2, 3, 4, 5, 6, 7], $values],
            [new IntSet([7, 5, 3, 1]), [0, 1, 2, 3], [7, 5, 3, 1]],
        ];
    }
}
