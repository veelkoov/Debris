<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\StringList;
use Veelkoov\Debris\StringSet;
use Veelkoov\Debris\StringStringMap;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[CoversClass(StringList::class)]
#[CoversClass(StringSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class MapFromTest extends TestCase
{
    #[Test]
    public function DList_mapFrom_withKeys(): void
    {
        $subject = DList::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value, string $key) => $key.$value);

        self::assertInstanceOf(DList::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['aA', 'bB'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function DList_mapFrom_noKeys(): void
    {
        $subject = DList::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value): string => "{$value}"); // Necessity to use "" is weird

        self::assertInstanceOf(DList::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['A', 'B'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function StringList_mapFrom_noKeys(): void
    {
        $subject = StringList::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value): string => "{$value}"); // Necessity to use "" is weird

        self::assertInstanceOf(StringList::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['A', 'B'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function DSet_mapFrom_withKeys(): void
    {
        $subject = DSet::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value, string $key) => $key.$value);

        self::assertInstanceOf(DSet::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['aA', 'bB'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function DSet_mapFrom_noKeys(): void
    {
        $subject = DSet::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value): string => "{$value}"); // Necessity to use "" is weird

        self::assertInstanceOf(DSet::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['A', 'B'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function StringSet_mapFrom_noKeys(): void
    {
        $subject = StringSet::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value): string => "{$value}"); // Necessity to use "" is weird

        self::assertInstanceOf(StringSet::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['A', 'B'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function DMap_mapFrom_withKeys(): void
    {
        $subject = DMap::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value, string $key) => ["{$key}", "{$value}"]);

        self::assertInstanceOf(DMap::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['a', 'b'], $subject->getKeysArray());
        self::assertSame(['A', 'B'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
        self::assertFalse($subject->hasKey('C'));
        self::assertFalse($subject->hasKey(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function DMap_mapFrom_noKeys(): void
    {
        $subject = DMap::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value) => ["{$value}", "{$value}"]);

        self::assertInstanceOf(DMap::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['A', 'B'], $subject->getKeysArray());
        self::assertSame(['A', 'B'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
        self::assertFalse($subject->hasKey('C'));
        self::assertFalse($subject->hasKey(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function StringStringMap_mapFrom_noKeys(): void
    {
        $subject = StringStringMap::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value) => ["{$value}", "{$value}"]);

        self::assertInstanceOf(DMap::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['A', 'B'], $subject->getKeysArray());
        self::assertSame(['A', 'B'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
        self::assertFalse($subject->hasKey('C'));
        self::assertFalse($subject->hasKey(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }
}
