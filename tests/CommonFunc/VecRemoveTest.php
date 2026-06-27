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
use Veelkoov\Debris\Vec;
use Veelkoov\Debris\Vecs\Base\DVec;
use Veelkoov\Debris\Vecs\IntVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(IntVec::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(Freezer::class)]
final class VecRemoveTest extends TestCase
{
    // @phpstan-ignore missingType.iterableValue,missingType.generics
    #[Test]
    #[DataProvider('provideRemoveCases')]
    public function remove(Vec $subject, callable $test, array $expected): void
    {
        $result = $test($subject);

        self::assertSame($subject, $result, 'Result should be the modified, original instance.');
        self::assertSame($expected, $result->getValuesArray());
    }

    public static function provideRemoveCases(): iterable
    {
        return [
            [new DVec([1, 2, 2, 3, 2, 4]), static fn (Vec $subject) => $subject->remove(2)->remove(2)->remove(4)
                ->remove(5), [1, 3, 2]],
            [new DVec([1, 2, 2, 3, 2, 4]), static fn (Vec $subject) => $subject->removeAll([2, 2, 4, 5]), [1, 3, 2]],
            [new IntVec([1, 2, 2, 3, 2, 4]), static fn (Vec $subject) => $subject->remove(2)->remove(2)->remove(4)
                ->remove(5), [1, 3, 2]],
            [new IntVec([1, 2, 2, 3, 2, 4]), static fn (Vec $subject) => $subject->removeAll([2, 2, 4, 5]), [1, 3, 2]],
        ];
    }

    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideRemove_onFrozenCases')]
    public function remove_onFrozen(Vec $subject, callable $test): void
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
            [new DVec([1]), static fn (Vec $subject) => $subject->remove(1)],
            [new DVec([1]), static fn (Vec $subject) => $subject->removeAll([1])],
            [new IntVec([1]), static fn (Vec $subject) => $subject->remove(1)],
            [new IntVec([1]), static fn (Vec $subject) => $subject->removeAll([1])],
        ];
    }
}
