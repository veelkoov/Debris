<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Pair;
use Veelkoov\Debris\Set;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\IntSet;

/**
 * @internal
 */
#[CoversClass(DIntOrStringSet::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class SetRandomTest extends TestCase
{
    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideRandomCases')]
    public function random(Set $subject): void
    {
        $firstResult = $subject->random();
        for ($i = 0; $i < 1000; ++$i) {
            $nextResult = $subject->random();
            if ($firstResult !== $nextResult) {
                break;
            }
        }

        self::assertNotSame($firstResult, $nextResult); // @phpstan-ignore variable.undefined (It will be assigned on first loop iteration)
    }

    public static function provideRandomCases(): iterable
    {
        return [
            [new DSet(self::getTestList())],
            [new IntSet(self::getTestList())],
        ];
    }

    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideRandom_emptyCases')]
    public function random_empty(Set $subject): void
    {
        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('The set is empty.');

        $subject->random();
    }

    public static function provideRandom_emptyCases(): iterable
    {
        return [
            [new DSet()],
            [new IntSet()],
        ];
    }

    /**
     * @return list<int>
     */
    private static function getTestList(): array
    {
        $input = range(1, 1000);

        self::assertSame(5, $input[5 - 1]);
        self::assertSame(995, $input[995 - 1]);

        return $input;
    }
}
