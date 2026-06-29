<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\NoSingleElementException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Set;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\IntSet;

/**
 * @internal
 */
#[CoversClass(DSet::class)]
#[CoversClass(DIntOrStringSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(DMap::class)]
#[UsesTrait(SimpleKeyMapTrait::class)]
#[UsesClass(MapKeyMapper::class)]
final class SetSingleTest extends TestCase
{
    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideSingle_exceptionsCases')]
    public function single_exceptions(Set $subject, string $expectedMessage): void
    {
        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage($expectedMessage);

        $subject->single();
    }

    public static function provideSingle_exceptionsCases(): iterable
    {
        $empty = [];
        $twoItems = [1, 2];

        $zeroErrMsg = 'The set has 0 items instead of exactly one.';
        $twoErrMsg = 'The set has 2 items instead of exactly one.';

        return [
            [new DSet($empty), $zeroErrMsg],
            [new IntSet($empty), $zeroErrMsg],
            [new DSet($twoItems), $twoErrMsg],
            [new IntSet($twoItems), $twoErrMsg],
        ];
    }

    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideSingle_worksCases')]
    public function single_works(Set $subject, mixed $expected): void
    {
        self::assertSame($expected, $subject->single());
    }

    public static function provideSingle_worksCases(): iterable
    {
        return [
            [new DSet([10]), 10],
            [new IntSet([20]), 20],
        ];
    }
}
