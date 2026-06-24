<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\NoSingleElementException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Vec;
use Veelkoov\Debris\Vecs\Base\DVec;
use Veelkoov\Debris\Vecs\IntVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[UsesClass(Freezer::class)]
final class VecSingleTest extends TestCase
{
    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideSingle_exceptionsCases')]
    public function single_exceptions(Vec $subject, string $expectedMessage): void
    {
        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage($expectedMessage);

        $subject->single();
    }

    public static function provideSingle_exceptionsCases(): iterable
    {
        $empty = [];
        $twoItems = [1, 2];

        $zeroErrMsg = 'The list has 0 items instead of exactly one.';
        $twoErrMsg = 'The list has 2 items instead of exactly one.';

        return [
            [new DVec($empty), $zeroErrMsg],
            [new IntVec($empty), $zeroErrMsg],
            [new DVec($twoItems), $twoErrMsg],
            [new IntVec($twoItems), $twoErrMsg],
        ];
    }

    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideSingle_worksCases')]
    public function single_works(Vec $subject, mixed $expected): void
    {
        self::assertSame($expected, $subject->single());
    }

    public static function provideSingle_worksCases(): iterable
    {
        return [
            [new DVec([10]), 10],
            [new IntVec([20]), 20],
        ];
    }
}
