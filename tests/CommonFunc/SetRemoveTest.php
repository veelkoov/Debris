<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\ChangingImmutableException;
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
#[CoversClass(IntSet::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class SetRemoveTest extends TestCase
{
    // @phpstan-ignore missingType.iterableValue,missingType.generics
    #[Test]
    #[DataProvider('provideRemoveCases')]
    public function remove(Set $subject, callable $test, array $expected): void
    {
        $result = $test($subject);

        self::assertSame($subject, $result, 'Result should be the modified, original instance.');
        self::assertSame($expected, $result->getValuesArray());
    }

    public static function provideRemoveCases(): iterable
    {
        return [
            [new DSet([1, 2, 3]), static fn (Set $subject) => $subject->remove(2)->remove(3)->remove(3)
                ->remove(4), [1]],
            [new DSet([1, 2, 3]), static fn (Set $subject) => $subject->removeAll([2, 3, 3, 4]), [1]],
            [new IntSet([1, 2, 3]), static fn (Set $subject) => $subject->remove(2)->remove(3)->remove(3)
                ->remove(4), [1]],
            [new IntSet([1, 2, 3]), static fn (Set $subject) => $subject->removeAll([2, 3, 3, 4]), [1]],
        ];
    }

    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideRemove_onFrozenCases')]
    public function remove_onFrozen(Set $subject, callable $test): void
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
            [new DSet([1]), static fn (Set $subject) => $subject->remove(1)],
            [new DSet([1]), static fn (Set $subject) => $subject->removeAll([1])],
            [new IntSet([1]), static fn (Set $subject) => $subject->remove(1)],
            [new IntSet([1]), static fn (Set $subject) => $subject->removeAll([1])],
        ];
    }
}
