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
use Veelkoov\Debris\Base\Internal\Pair;
use Veelkoov\Debris\Maps\IntToInt;
use Veelkoov\Debris\Maps\IntToString;
use Veelkoov\Debris\Maps\StringToBool;
use Veelkoov\Debris\Maps\StringToInt;
use Veelkoov\Debris\Maps\StringToString;

/**
 * @internal
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
     * @return list<array{
     *           DMap<covariant scalar|object|null, covariant scalar|object|null>,
     *           list<array<string, mixed>>,
     *           string, string, bool,
     *         }>
     */
    public static function fromRowsDataProvider(): array
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

    /**
     * @param DMap<null|object|scalar, null|object|scalar> $intance
     * @param array<string, mixed>                         $input
     * @param literal-string                               $keyKey
     * @param literal-string                               $valueKey
     */
    #[Test]
    #[DataProvider('fromRowsDataProvider')]
    public function fromRows(DMap $intance, array $input, string $keyKey, string $valueKey, bool $allowed): void
    {
        try {
            $intance::fromRows($input, $keyKey, $valueKey);

            self::assertTrue($allowed, 'Should not have been allowed.');
        } catch (\TypeError) {
            self::assertFalse($allowed, 'Should have been allowed.');
        }
    }
}
