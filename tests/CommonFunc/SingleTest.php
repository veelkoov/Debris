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
use Veelkoov\Debris\Exception\NoSingleElementException;
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
final class SingleTest extends TestCase
{
    #[Test]
    public function DVec_single_throwsOnEmpty(): void
    {
        $subject = new DVec();

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The list has 0 items instead of exactly one.');

        $subject->single();
    }

    #[Test]
    public function DVec_single_throwsOnMultiple(): void
    {
        $subject = new DVec([1, 2]);

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The list has 2 items instead of exactly one.');

        $subject->single();
    }

    #[Test]
    public function DVec_single_works(): void
    {
        $subject = new DVec([10]);

        self::assertSame(10, $subject->single());
    }

    #[Test]
    public function DSet_single_throwsOnEmpty(): void
    {
        $subject = new DSet();

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The set has 0 items instead of exactly one.');

        $subject->single();
    }

    #[Test]
    public function DSet_single_throwsOnMultiple(): void
    {
        $subject = new DSet([1, 2]);

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The set has 2 items instead of exactly one.');

        $subject->single();
    }

    #[Test]
    public function DSet_single_works(): void
    {
        $subject = new DSet([10]);

        self::assertSame(10, $subject->single());
    }

    #[Test]
    public function DMap_single_throwsOnEmpty(): void
    {
        $subject = new DMap();

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The map has 0 items instead of exactly one.');

        $subject->single();
    }

    #[Test]
    public function DMap_single_throwsOnMultiple(): void
    {
        $subject = new DMap([1 => 1, 2 => 2]);

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The map has 2 items instead of exactly one.');

        $subject->single();
    }

    #[Test]
    public function DMap_single_works(): void
    {
        $subject = new DMap([10 => 20]);

        self::assertSame(10, $subject->single()->key);
        self::assertSame(20, $subject->single()->value);
    }

    #[Test]
    public function DMap_singleKey_throwsOnEmpty(): void
    {
        $subject = new DMap();

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The map has 0 items instead of exactly one.');

        $subject->singleKey();
    }

    #[Test]
    public function DMap_singleKey_throwsOnMultiple(): void
    {
        $subject = new DMap([1 => 1, 2 => 2]);

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The map has 2 items instead of exactly one.');

        $subject->singleKey();
    }

    #[Test]
    public function DMap_singleKey_works(): void
    {
        $subject = new DMap([10 => 20]);

        self::assertSame(10, $subject->singleKey());
    }

    #[Test]
    public function DMap_singleValue_throwsOnEmpty(): void
    {
        $subject = new DMap();

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The map has 0 items instead of exactly one.');

        $subject->singleValue();
    }

    #[Test]
    public function DMap_singleValue_throwsOnMultiple(): void
    {
        $subject = new DMap([1 => 1, 2 => 2]);

        self::expectException(NoSingleElementException::class);
        self::expectExceptionMessage('The map has 2 items instead of exactly one.');

        $subject->singleValue();
    }

    #[Test]
    public function DMap_singleValue_works(): void
    {
        $subject = new DMap([10 => 20]);

        self::assertSame(20, $subject->singleValue());
    }
}
