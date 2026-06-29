<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\StringToString;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\StringSet;
use Veelkoov\Debris\Vecs\Base\DVec;
use Veelkoov\Debris\Vecs\StringVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[CoversClass(StringVec::class)]
#[CoversClass(StringSet::class)]
#[CoversClass(StringToString::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class MapFromTest extends TestCase
{
    #[Test]
    public function DVec_mapFrom_withKeys(): void
    {
        $subject = DVec::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value, string $key) => $key.$value);

        self::assertInstanceOf(DVec::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['aA', 'bB'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function DVec_mapFrom_noKeys(): void
    {
        $subject = DVec::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value): string => "{$value}"); // Necessity to use "" is weird

        self::assertInstanceOf(DVec::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['A', 'B'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function StringVec_mapFrom_noKeys(): void
    {
        $subject = StringVec::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value): string => "{$value}"); // Necessity to use "" is weird

        self::assertInstanceOf(StringVec::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
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
        $subject = StringToString::mapFrom(['a' => 'A', 'b' => 'B'], static fn (string $value) => ["{$value}", "{$value}"]);

        self::assertInstanceOf(Map::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)
        self::assertSame(['A', 'B'], $subject->getKeysArray());
        self::assertSame(['A', 'B'], $subject->getValuesArray());

        self::assertFalse($subject->contains('C'));
        self::assertFalse($subject->contains(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
        self::assertFalse($subject->hasKey('C'));
        self::assertFalse($subject->hasKey(1)); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }
}
