<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Maps\IntToString;
use Veelkoov\Debris\Maps\Pair;
use Veelkoov\Debris\Maps\StringToInt;
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
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
#[UsesTrait(SimpleKeyMapTrait::class)]
final class MapTest extends TestCase
{
    #[Test]
    public function DVec_map(): void
    {
        $result = (new DVec([1, 2, 3]))
            ->map(static fn (int $value) => $value * 2)
        ;

        self::assertSame([2, 4, 6], $result->getValuesArray());
    }

    #[Test]
    public function DVec_mapInto(): void
    {
        $target = new StringVec();

        $result = (new DVec([1, 2, 3]))
            ->mapInto($target, static fn (int $value) => (string) ($value * 2))
        ;

        self::assertInstanceOf(StringVec::class, $result); // @phpstan-ignore staticMethod.alreadyNarrowedType (Unneded ignore means broken annotations)
        self::assertSame($target, $result);
        self::assertSame(['2', '4', '6'], $result->getValuesArray());
    }

    #[Test]
    public function DSet_map(): void
    {
        // Making sure 5 * 2 % 10 will be deduplicated with 0
        $result = (new DSet([0, 1, 2, 5]))
            ->map(static fn (int $value) => $value * 2 % 10)
        ;

        self::assertSame([0, 2, 4], $result->getValuesArray());
    }

    #[Test]
    public function DSet_mapInto(): void
    {
        $target = new StringSet();

        // Making sure 5 * 2 % 10 will be deduplicated with 0
        $result = (new DSet([0, 1, 2, 5]))
            ->mapInto(static fn (int $value) => (string) ($value * 2 % 10), $target)
        ;

        self::assertInstanceOf(StringSet::class, $result); // @phpstan-ignore staticMethod.alreadyNarrowedType (Unneded ignore means broken annotations)
        self::assertSame($target, $result);
        self::assertSame(['0', '2', '4'], $result->getValuesArray());
    }

    #[Test]
    public function DMap_map(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->map(static fn (string $key, int $value) => [$key.$key, $value * 2])
        ;

        self::assertSame(['aa', 'bb', 'cc'], $result->getKeysArray());
        self::assertSame([2, 4, 6], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapInto(): void
    {
        $target = new IntToString();

        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->mapInto($target, static fn (string $key, int $value) => [$value * 2, $key.$key])
        ;

        self::assertInstanceOf(IntToString::class, $result); // @phpstan-ignore staticMethod.alreadyNarrowedType (Unneded ignore means broken annotations)
        self::assertSame($target, $result);
        self::assertSame([2, 4, 6], $result->getKeysArray());
        self::assertSame(['aa', 'bb', 'cc'], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapKeys(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->mapKeys(static fn (string $key) => $key.$key)
        ;

        self::assertSame(['aa', 'bb', 'cc'], $result->getKeysArray());
        self::assertSame([1, 2, 3], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapKeysInto(): void
    {
        $target = new IntToString();

        $result = (new DMap(['a' => 'aa', 'b' => 'bb', 'c' => 'cc']))
            ->mapKeysInto($target, static fn (string $key) => 10 + (\ord($key) - \ord('a')))
        ;

        self::assertInstanceOf(IntToString::class, $result); // @phpstan-ignore staticMethod.alreadyNarrowedType (Unneded ignore means broken annotations)
        self::assertSame($target, $result);
        self::assertSame([10, 11, 12], $result->getKeysArray());
        self::assertSame(['aa', 'bb', 'cc'], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapValues(): void
    {
        $result = (new DMap(['a' => 1, 'b' => 2, 'c' => 3]))
            ->mapValues(static fn (int $value) => $value * 2)
        ;

        self::assertSame(['a', 'b', 'c'], $result->getKeysArray());
        self::assertSame([2, 4, 6], $result->getValuesArray());
    }

    #[Test]
    public function DMap_mapValuesInto(): void
    {
        $target = new StringToInt();

        $result = (new DMap(['a' => '1', 'b' => '2', 'c' => '3']))
            ->mapValuesInto($target, static fn (string $value) => 2 * (int) $value)
        ;

        self::assertInstanceOf(StringToInt::class, $result); // @phpstan-ignore staticMethod.alreadyNarrowedType (Unneded ignore means broken annotations)
        self::assertSame($target, $result);
        self::assertSame(['a', 'b', 'c'], $result->getKeysArray());
        self::assertSame([2, 4, 6], $result->getValuesArray());
    }
}
