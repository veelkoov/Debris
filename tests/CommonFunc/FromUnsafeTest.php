<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DVec;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Vecs\IntVec;
use Veelkoov\Debris\Maps\IntToString;
use Veelkoov\Debris\Maps\StringToBool;
use Veelkoov\Debris\Sets\IntSet;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(IntVec::class)]
#[CoversClass(IntSet::class)]
#[CoversClass(IntToString::class)]
#[CoversClass(StringToBool::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class FromUnsafeTest extends TestCase
{
    #[Test]
    public function IntVec_fromUnsafe(): void
    {
        // Basic success test
        self::assertSame([1, 2, 3, 4], IntVec::fromUnsafe([1, 2, 3, 4])->getValuesArray());

        // Keys in the source don't matter
        self::assertSame([1, 10], IntVec::fromUnsafe(['b' => 1, 5 => 10])->getValuesArray());

        try {
            IntVec::fromUnsafe([1, 2, 'a', 4]);
            self::fail('Value "a" should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }

        try {
            IntVec::fromUnsafe(new \stdClass());
            self::fail('Non-iterable should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }
    }

    #[Test]
    public function IntSet_fromUnsafe(): void
    {
        // Basic success test, deduplication works
        self::assertSame([1, 2, 3, 4], IntSet::fromUnsafe([1, 2, 3, 4, 1, 2])->getValuesArray());

        // Keys in the source don't matter, deduplication works
        self::assertSame([1, 10], IntSet::fromUnsafe(['b' => 1, 5 => 10, 6 => 10])->getValuesArray());

        try {
            IntSet::fromUnsafe([1, 2, 'a', 4]);
            self::fail('Value "a" should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }

        try {
            IntSet::fromUnsafe(new \stdClass());
            self::fail('Non-iterable should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }
    }

    #[Test]
    public function IntToString_fromUnsafe(): void
    {
        // Basic success test
        $result = IntToString::fromUnsafe([1 => 'aa', 2 => 'bb', 3 => 'cc', 4 => 'dd']);
        self::assertSame([1 => 'aa', 2 => 'bb', 3 => 'cc', 4 => 'dd'], $result->toArray());

        try {
            IntToString::fromUnsafe([1 => 'aa', 'b' => 'bb', 3 => 'cc', 4 => 'dd']);
            self::fail('Key "b" should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }

        try {
            IntToString::fromUnsafe([1 => 'aa', 2 => 2, 3 => 'cc', 4 => 'dd']);
            self::fail('Value 2 should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }

        try {
            IntToString::fromUnsafe(new \stdClass());
            self::fail('Non-iterable should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }
    }

    #[Test]
    public function StringToBool_fromUnsafe(): void
    {
        // Basic success test
        $result = StringToBool::fromUnsafe(['a' => true, 'b' => true, 'c' => false, 'd' => false]);
        self::assertSame(['a' => true, 'b' => true, 'c' => false, 'd' => false], $result->toArray());

        try {
            StringToBool::fromUnsafe(['a' => true, 2 => true, 'c' => false, 'd' => false]);
            self::fail('Key 2 should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }

        try {
            StringToBool::fromUnsafe(['a' => true, 'b' => 'false', 'c' => false, 'd' => false]);
            self::fail('Value "false" should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }

        try {
            StringToBool::fromUnsafe(new \stdClass());
            self::fail('Non-iterable should not have been accepted.');
        } catch (\InvalidArgumentException) {
            // Expected
        }
    }
}
