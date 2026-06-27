<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Vec;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[UsesClass(Freezer::class)]
final class VecIterationTest extends TestCase
{
    // @phpstan-ignore missingType.iterableValue,missingType.iterableValue,missingType.generics
    #[Test]
    #[DataProvider('provideIterationCases')]
    public function iteration(Vec $subject, array $expectedKeys, array $expectedValues): void
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
            [new DVec($values), [0, 1, 2, 3, 4, 5, 6, 7], $values],
        ];
    }
}
