<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Set;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\IntSet;
use Veelkoov\Debris\Sets\StringSet;

/**
 * @internal
 */
#[CoversClass(DIntOrStringSet::class)]
#[CoversClass(DSet::class)]
#[UsesClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesTrait(SimpleKeyMapTrait::class)]
final class SetSliceTest extends TestCase
{
    // @phpstan-ignore missingType.generics,missingType.iterableValue
    #[Test]
    #[DataProvider('provideSliceCases')]
    public function slice(Set $subject, callable $test, array $expected): void
    {
        $result = $test($subject);

        self::assertSame($expected, $result->getValuesArray()); // @phpstan-ignore method.nonObject
    }

    public static function provideSliceCases(): iterable
    {
        return [
            [new DSet([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                static fn (Set $subject) => $subject->slice(2, 4), [3, 4, 5, 6]],
            [new DSet([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                static fn (Set $subject) => $subject->slice(-5, -2), [6, 7, 8]],
            [new IntSet([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                static fn (Set $subject) => $subject->slice(2, 4), [3, 4, 5, 6]],
            [new IntSet([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                static fn (Set $subject) => $subject->slice(-5, -2), [6, 7, 8]],
            [new StringSet(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j']),
                static fn (Set $subject) => $subject->slice(2, 4), ['c', 'd', 'e', 'f']],
            [new StringSet(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j']),
                static fn (Set $subject) => $subject->slice(-5, -2), ['f', 'g', 'h']],
        ];
    }
}
