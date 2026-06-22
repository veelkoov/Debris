<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Set;
use Veelkoov\Debris\Sets\AnySet;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\IntSet;

/**
 * @internal
 */
#[CoversClass(DIntOrStringSet::class)]
#[CoversClass(DSet::class)]
#[UsesClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesTrait(SimpleKeyMapTrait::class)]
final class SetAnyAllTest extends TestCase
{
    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('casesDataProvider')]
    public function _any(Set $subject, callable $anyTrue, callable $anyFalse, callable $_, callable $__): void
    {
        self::assertTrue($subject->any($anyTrue));
        self::assertFalse($subject->any($anyFalse));
    }

    // @phpstan-ignore missingType.generics
    #[Test]
    #[DataProvider('casesDataProvider')]
    public function all(Set $subject, callable $_, callable $__, callable $allTrue, callable $allFalse): void
    {
        self::assertTrue($subject->all($allTrue));
        self::assertFalse($subject->all($allFalse));
    }

    /**
     * @return iterable<array{Set, callable, callable, callable, callable}>
     */
    public static function casesDataProvider(): iterable
    {
        return [
            [
                new AnySet([1, '2', false]),
                is_bool(...), // any true
                is_float(...), // any false
                is_scalar(...), // all true
                is_numeric(...), // all false
            ],
            [
                new IntSet([1, 2, 3]),
                static fn (int $value) => $value > 2, // any true
                static fn (int $value) => $value > 100, // any false
                static fn (int $value) => $value > -100, // all true
                static fn (int $value) => $value > 1, // all false
            ],
        ];
    }
}
