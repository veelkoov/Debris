<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\IntToInt;
use Veelkoov\Debris\Maps\IntToString;
use Veelkoov\Debris\Maps\Pair;
use Veelkoov\Debris\Maps\StringToBool;
use Veelkoov\Debris\Maps\StringToInt;
use Veelkoov\Debris\Maps\StringToString;

/**
 * @internal
 *
 * @phpstan-type TTestMap DMap<covariant scalar|object|null, covariant scalar|object|null>
 */
#[CoversClass(IntToInt::class)]
#[CoversClass(IntToString::class)]
#[CoversClass(StringToBool::class)]
#[CoversClass(StringToInt::class)]
#[CoversClass(StringToString::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class DMapFromRowsTest extends TestCase
{
    /**
     * @param TTestMap                   $instance
     * @param list<array<string, mixed>> $input
     * @param literal-string             $keyKey
     * @param literal-string             $valueKey
     */
    #[Test]
    #[DataProvider('provideFromRowsCases')]
    public function fromRows(DMap $instance, array $input, string $keyKey, string $valueKey, bool $allowed): void
    {
        try {
            $instance::fromRows($input, $keyKey, $valueKey);

            self::assertTrue($allowed, 'Should not have been allowed.');
        } catch (\TypeError) {
            self::assertFalse($allowed, 'Should have been allowed.');
        }
    }

    /**
     * @return list<array{TTestMap, list<array<string, mixed>>, string, string, bool}>
     */
    public static function provideFromRowsCases(): iterable
    {
        $testRow = [
            'int' => 1,
            'string' => 'abc',
            'bool' => true,
        ];

        return [
            [new IntToInt(), [$testRow], 'string', 'int', false],
            [new IntToInt(), [$testRow], 'int', 'string', false],
            [new IntToInt(), [$testRow], 'int', 'int', true],

            [new IntToString(), [$testRow], 'string', 'string', false],
            [new IntToString(), [$testRow], 'int', 'int', false],
            [new IntToString(), [$testRow], 'int', 'string', true],

            [new StringToBool(), [$testRow], 'int', 'bool', false],
            [new StringToBool(), [$testRow], 'string', 'int', false],
            [new StringToBool(), [$testRow], 'string', 'bool', true],

            [new StringToInt(), [$testRow], 'int', 'int', false],
            [new StringToInt(), [$testRow], 'string', 'string', false],
            [new StringToInt(), [$testRow], 'string', 'int', true],

            [new StringToString(), [$testRow], 'int', 'string', false],
            [new StringToString(), [$testRow], 'string', 'int', false],
            [new StringToString(), [$testRow], 'string', 'string', true],
        ];
    }
}
