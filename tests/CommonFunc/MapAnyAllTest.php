<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class MapAnyAllTest extends TestCase
{
    #[Test]
    public function _any(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->any(static fn (int $key, string $value) => $key > 2 && $value > 'b'));
        self::assertFalse($subject->any(static fn (int $key, string $value) => $key > 3 && $value > 'c'));
    }

    #[Test]
    public function all(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->all(static fn (int $key, string $value) => $key > 0 && $value > '`'));
        self::assertFalse($subject->all(static fn (int $key, string $value) => $key > 1 && $value > 'a'));
    }

    #[Test]
    public function anyValue(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->anyValue(static fn (string $value) => $value > 'b'));
        self::assertFalse($subject->anyValue(static fn (string $value) => $value > 'c'));
    }

    #[Test]
    public function anyKey(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->anyKey(static fn (int $key) => $key > 2));
        self::assertFalse($subject->anyKey(static fn (int $key) => $key > 3));
    }

    #[Test]
    public function allValues(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->allValues(static fn (string $value) => $value > '`'));
        self::assertFalse($subject->allValues(static fn (string $value) => $value > 'a'));
    }

    #[Test]
    public function allKeys(): void
    {
        $subject = new DMap([1 => 'a', 2 => 'b', 3 => 'c']);

        self::assertTrue($subject->allKeys(static fn (int $key) => $key > 0));
        self::assertFalse($subject->allKeys(static fn (int $key) => $key > 1));
    }
}
