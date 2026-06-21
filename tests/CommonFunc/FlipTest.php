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
use Veelkoov\Debris\Maps\Base\DIntMap;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\DStringMap;
use Veelkoov\Debris\Maps\IntToInt;
use Veelkoov\Debris\Maps\IntToString;
use Veelkoov\Debris\Maps\StringToInt;
use Veelkoov\Debris\Maps\StringToString;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[CoversClass(DStringMap::class)]
#[CoversClass(DIntMap::class)]
#[CoversClass(IntToInt::class)]
#[CoversClass(IntToString::class)]
#[CoversClass(StringToInt::class)]
#[CoversClass(StringToString::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class FlipTest extends TestCase
{
    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('provideDMap_flipCases')]
    public function DMap_flip(Map $subject, string $class, mixed $key, mixed $value): void
    {
        $result = $subject->flip();

        self::assertSame($class, $result::class);
        self::assertCount(1, $result);
        self::assertSame($value, $result->get($key)); // @phpstan-ignore argument.type
    }

    /**
     * @return iterable<array{Map, string, mixed, mixed}>
     */
    public static function provideDMap_flipCases(): iterable
    {
        $anObject = new \stdClass();

        return [
            [new DMap(['a true' => true]), DMap::class, true, 'a true'],
            [new DStringMap(['a null' => null]), DMap::class, null, 'a null'],
            [new DIntMap([10 => $anObject]), DMap::class, $anObject, 10],
            [new IntToInt([20 => 2000]), IntToInt::class, 2000, 20],
            [new IntToString([30 => 'thirty']), StringToInt::class, 'thirty', 30],
            [new StringToInt(['five' => 5]), IntToString::class, 5, 'five'],
            [new StringToString(['a key' => 'a value']), StringToString::class, 'a value', 'a key'],
        ];
    }
}
