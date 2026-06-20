<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\DVec;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class AnyAllTest extends TestCase
{
    #[Test]
    public function DVec_any(): void
    {
        $subject = new DVec([1, 2, 3]);

        self::assertTrue($subject->any(static fn (int $value) => $value > 2));
        self::assertFalse($subject->any(static fn (int $value) => $value > 3));
    }

    #[Test]
    public function DVec_all(): void
    {
        $subject = new DVec([1, 2, 3]);

        self::assertTrue($subject->all(static fn (int $value) => $value > 0));
        self::assertFalse($subject->all(static fn (int $value) => $value > 1));
    }

    #[Test]
    public function DSet_any(): void
    {
        $subject = new DSet([1, 2, 3]);

        self::assertTrue($subject->any(static fn (int $value) => $value > 2));
        self::assertFalse($subject->any(static fn (int $value) => $value > 3));
    }

    #[Test]
    public function DSet_all(): void
    {
        $subject = new DSet([1, 2, 3]);

        self::assertTrue($subject->all(static fn (int $value) => $value > 0));
        self::assertFalse($subject->all(static fn (int $value) => $value > 1));
    }

    #[Test]
    public function DMap_any(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->any(static fn (int $key, string $value) => $key > 2 && $value > 'b'));
        self::assertFalse($subject->any(static fn (int $key, string $value) => $key > 3 && $value > 'c'));
    }

    #[Test]
    public function DMap_all(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->all(static fn (int $key, string $value) => $key > 0 && $value > '`'));
        self::assertFalse($subject->all(static fn (int $key, string $value) => $key > 1 && $value > 'a'));
    }

    #[Test]
    public function DMap_anyKey(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->anyKey(static fn (int $key) => $key > 2));
        self::assertFalse($subject->anyKey(static fn (int $key) => $key > 3));
    }

    #[Test]
    public function DMap_allKeys(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->allKeys(static fn (int $key) => $key > 0));
        self::assertFalse($subject->allKeys(static fn (int $key) => $key > 1));
    }

    #[Test]
    public function DMap_anyValue(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->anyValue(static fn (string $value) => $value > 'b'));
        self::assertFalse($subject->anyValue(static fn (string $value) => $value > 'c'));
    }

    #[Test]
    public function DMap_allValues(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->allValues(static fn (string $value) => $value > '`'));
        self::assertFalse($subject->allValues(static fn (string $value) => $value > 'a'));
    }
}
