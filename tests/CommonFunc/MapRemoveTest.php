<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\ChangingImmutableException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Maps\StringToInt;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[CoversTrait(SimpleKeyMapTrait::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class MapRemoveTest extends TestCase
{
    // @phpstan-ignore missingType.iterableValue,missingType.iterableValue,missingType.generics
    #[Test]
    #[DataProvider('provideRemoveCases')]
    public function remove(Map $subject, callable $test, array $expectedKeys, array $expectedValues): void
    {
        $result = $test($subject);

        self::assertSame($subject, $result, 'Result should be the modified, original instance.');
        self::assertSame($expectedKeys, $result->getKeysArray());
        self::assertSame($expectedValues, $result->getValuesArray());
    }

    public static function provideRemoveCases(): iterable
    {
        return [
            [new DMap(['a' => 1, 'b' => 2, 'c' => 2, 'd' => 4]), static fn (Map $subject) => $subject->removeValue(2)
                ->removeValue(4)->removeValue(5), ['a', 'c'], [1, 2]],
            [new StringToInt(['a' => 1, 'b' => 2, 'c' => 2, 'd' => 4]), static fn (Map $subject) => $subject->removeValue(2)
                ->removeValue(4)->removeValue(5), ['a', 'c'], [1, 2]],
            [new DMap(['a' => 1, 'b' => 2]), static fn (Map $subject) => $subject->removeKey('a')->removeKey('a')
                ->removeKey('c'), ['b'], [2]],
            [new StringToInt(['a' => 1, 'b' => 2]), static fn (Map $subject) => $subject->removeKey('a')->removeKey('a')
                ->removeKey('c'), ['b'], [2]],
            [new DMap(['a' => 1, 'b' => 2, 'c' => 2, 'd' => 4]), static fn (Map $subject) => $subject
                ->removeAllValues([2, 4, 5]), ['a', 'c'], [1, 2]],
            [new StringToInt(['a' => 1, 'b' => 2, 'c' => 2, 'd' => 4]), static fn (Map $subject) => $subject
                ->removeAllValues([2, 4, 5]), ['a', 'c'], [1, 2]],
            [new DMap(['a' => 1, 'b' => 2]), static fn (Map $subject) => $subject->removeAllKeys(['a', 'a', 'c']),
                ['b'], [2]],
            [new StringToInt(['a' => 1, 'b' => 2]), static fn (Map $subject) => $subject->removeAllKeys(['a', 'a', 'c']),
                ['b'], [2]],
        ];
    }

    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideRemove_onFrozenCases')]
    public function remove_onFrozen(Map $subject, callable $test): void
    {
        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance.');

        try {
            $test($subject);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }

    public static function provideRemove_onFrozenCases(): iterable
    {
        return [
            [new DMap(['a' => 1]), static fn (Map $subject) => $subject->removeValue(1)],
            [new DMap(['a' => 1]), static fn (Map $subject) => $subject->removeKey('a')],
            [new DMap(['a' => 1]), static fn (Map $subject) => $subject->removeAllValues([1])],
            [new DMap(['a' => 1]), static fn (Map $subject) => $subject->removeAllKeys(['a'])],
            [new StringToInt(['a' => 1]), static fn (Map $subject) => $subject->removeValue(1)],
            [new StringToInt(['a' => 1]), static fn (Map $subject) => $subject->removeKey('a')],
            [new StringToInt(['a' => 1]), static fn (Map $subject) => $subject->removeAllValues([1])],
            [new StringToInt(['a' => 1]), static fn (Map $subject) => $subject->removeAllKeys(['a'])],
        ];
    }
}
