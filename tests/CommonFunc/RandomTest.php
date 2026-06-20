<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DVec;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Maps\Pair;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
#[UsesClass(Pair::class)]
final class RandomTest extends TestCase
{
    #[Test]
    public function DVec_random(): void
    {
        $subject = new DVec(self::getTestList());

        $firstResult = $subject->random();
        for ($i = 0; $i < 100; ++$i) {
            $nextResult = $subject->random();
            if ($firstResult !== $nextResult) {
                break;
            }
        }

        self::assertNotSame($firstResult, $nextResult); // @phpstan-ignore variable.undefined (It will be assigned on first loop iteration)
    }

    #[Test]
    public function DVec_random_empty(): void
    {
        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('The list is empty.');

        (new DVec())->random();
    }

    #[Test]
    public function DSet_random(): void
    {
        $subject = new DSet(self::getTestList());

        $firstResult = $subject->random();
        for ($i = 0; $i < 100; ++$i) {
            $nextResult = $subject->random();
            if ($firstResult !== $nextResult) {
                break;
            }
        }

        self::assertNotSame($firstResult, $nextResult); // @phpstan-ignore variable.undefined (It will be assigned on first loop iteration)
    }

    #[Test]
    public function DSet_random_empty(): void
    {
        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('The set is empty.');

        (new DSet())->random();
    }

    #[Test]
    public function DMap_random(): void
    {
        $subject = new DMap(self::getTestIntIntArray());

        $firstResult = $subject->random();
        for ($i = 0; $i < 1000; ++$i) {
            $nextResult = $subject->random();
            if ($firstResult !== $nextResult) {
                break;
            }
        }

        self::assertNotSame($firstResult->key, $nextResult->key); // @phpstan-ignore variable.undefined (It will be assigned on first loop iteration)
    }

    #[Test]
    public function DMap_random_empty(): void
    {
        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('The map is empty.');

        (new DMap())->random();
    }

    #[Test]
    public function DMap_randomKey(): void
    {
        $subject = new DMap(self::getTestIntIntArray());

        $firstResult = $subject->randomKey();
        for ($i = 0; $i < 100; ++$i) {
            $nextResult = $subject->randomKey();
            if ($firstResult !== $nextResult) {
                break;
            }
        }

        self::assertNotSame($firstResult, $nextResult); // @phpstan-ignore variable.undefined (It will be assigned on first loop iteration)
    }

    #[Test]
    public function DMap_randomKey_empty(): void
    {
        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('The map is empty.');

        (new DMap())->randomKey();
    }

    #[Test]
    public function DMap_randomValue(): void
    {
        $subject = new DMap(self::getTestIntIntArray());

        $firstResult = $subject->randomValue();
        for ($i = 0; $i < 100; ++$i) {
            $nextResult = $subject->randomValue();
            if ($firstResult !== $nextResult) {
                break;
            }
        }

        self::assertNotSame($firstResult, $nextResult); // @phpstan-ignore variable.undefined (It will be assigned on first loop iteration)
    }

    #[Test]
    public function DMap_randomValue_empty(): void
    {
        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('The map is empty.');

        (new DMap())->randomValue();
    }

    /**
     * @return list<int>
     */
    private static function getTestList(): array
    {
        $input = range(1, 100);

        self::assertSame(5, $input[5 - 1]);
        self::assertSame(95, $input[95 - 1]);

        return $input;
    }

    /**
     * @return array<int, int>
     */
    private static function getTestIntIntArray(): array
    {
        $keys = range(1, 100);
        $values = range(50001, 50100);
        $input = array_combine($keys, $values);

        self::assertSame(50005, $input[5]);
        self::assertSame(50095, $input[95]);

        return $input;
    }
}
