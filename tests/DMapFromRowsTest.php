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
use Veelkoov\Debris\IntIntMap;
use Veelkoov\Debris\IntStringMap;
use Veelkoov\Debris\StringBoolMap;
use Veelkoov\Debris\StringIntMap;
use Veelkoov\Debris\StringStringMap;

/**
 * @internal
 */
#[CoversClass(IntIntMap::class)]
#[CoversClass(IntStringMap::class)]
#[CoversClass(StringBoolMap::class)]
#[CoversClass(StringIntMap::class)]
#[CoversClass(StringStringMap::class)]
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
            [new IntIntMap(), [$testRow], 'string', 'int', false],
            [new IntIntMap(), [$testRow], 'int', 'string', false],
            [new IntIntMap(), [$testRow], 'int', 'int', true],

            [new IntStringMap(), [$testRow], 'string', 'string', false],
            [new IntStringMap(), [$testRow], 'int', 'int', false],
            [new IntStringMap(), [$testRow], 'int', 'string', true],

            [new StringBoolMap(), [$testRow], 'int', 'bool', false],
            [new StringBoolMap(), [$testRow], 'string', 'int', false],
            [new StringBoolMap(), [$testRow], 'string', 'bool', true],

            [new StringIntMap(), [$testRow], 'int', 'int', false],
            [new StringIntMap(), [$testRow], 'string', 'string', false],
            [new StringIntMap(), [$testRow], 'string', 'int', true],

            [new StringStringMap(), [$testRow], 'int', 'string', false],
            [new StringStringMap(), [$testRow], 'string', 'int', false],
            [new StringStringMap(), [$testRow], 'string', 'string', true],
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
