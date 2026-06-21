<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\ChangingImmutableException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class RemoveAllTest extends TestCase
{
    #[Test]
    public function DVec_removeAll(): void
    {
        $subject = new DVec([1, 2, 2, 3, 2, 4]);

        $result = $subject->removeAll([2, 2, 4, 5]);

        self::assertSame($subject, $result, 'Result should be the modified, original instance');
        self::assertSame([1, 3, 2], $result->getValuesArray());
    }

    #[Test]
    public function DSet_removeAll(): void
    {
        $subject = new DSet([1, 2, 3]);

        $result = $subject->removeAll([2, 3, 3, 4]);

        self::assertSame($subject, $result, 'Result should be the modified, original instance');
        self::assertSame([1], $result->getValuesArray());
    }

    #[Test]
    public function DMap_removeAllValues(): void
    {
        $subject = new DMap(['a' => 1, 'b' => 2, 'c' => 2, 'd' => 4]);

        $result = $subject->removeAllValues([2, 4, 5]);

        self::assertSame($subject, $result, 'Result should be the modified, original instance');
        self::assertSame(['a', 'c'], $result->getKeysArray());
        self::assertSame([1, 2], $result->getValuesArray());
    }

    #[Test]
    public function DMap_removeAllKeys(): void
    {
        $subject = new DMap(['a' => 1, 'b' => 2]);

        $result = $subject->removeAllKeys(['a', 'a', 'c']);

        self::assertSame($subject, $result, 'Result should be the modified, original instance');
        self::assertSame(['b'], $result->getKeysArray());
        self::assertSame([2], $result->getValuesArray());
    }

    #[Test]
    public function DVec_removeAll_onFrozen(): void
    {
        $subject = new DVec([1]);

        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance');

        try {
            $subject->removeAll([1]);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }

    #[Test]
    public function DSet_removeAll_onFrozen(): void
    {
        $subject = new DSet([1]);

        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance');

        try {
            $subject->removeAll([1]);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }

    #[Test]
    public function DMap_removeAllValues_onFrozen(): void
    {
        $subject = new DMap(['a' => 1]);

        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance');

        try {
            $subject->removeAllValues([1]);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }

    #[Test]
    public function DMap_removeAllKeys_onFrozen(): void
    {
        $subject = new DMap(['a' => 1]);

        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance');

        try {
            $subject->removeAllKeys(['a']);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }
}
