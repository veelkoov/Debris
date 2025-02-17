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
use Veelkoov\Debris\Exception\ChangingImmutableException;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class RemoveAllUnsetAllTest extends TestCase
{
    #[Test]
    public function DList_removeAll(): void
    {
        $subject = new DList([1, 2, 2, 3, 2, 4]);

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
    public function DMap_removeAll(): void
    {
        $subject = new DMap(['a' => 1, 'b' => 2, 'c' => 2, 'd' => 4]);

        $result = $subject->removeAll([2, 4, 5]);

        self::assertSame($subject, $result, 'Result should be the modified, original instance');
        self::assertSame(['a', 'c'], $result->getKeysArray());
        self::assertSame([1, 2], $result->getValuesArray());
    }

    #[Test]
    public function DMap_unsetAll(): void
    {
        $subject = new DMap(['a' => 1, 'b' => 2]);

        $result = $subject->unsetAll(['a', 'a', 'c']);

        self::assertSame($subject, $result, 'Result should be the modified, original instance');
        self::assertSame(['b'], $result->getKeysArray());
        self::assertSame([2], $result->getValuesArray());
    }

    #[Test]
    public function DList_removeAll_onFrozen(): void
    {
        $subject = new DList([1]);

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
    public function DMap_removeAll_onFrozen(): void
    {
        $subject = new DMap(['a' => 1]);

        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance');

        try {
            $subject->removeAll([1]);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }

    #[Test]
    public function DMap_unsetAll_onFrozen(): void
    {
        $subject = new DMap(['a' => 1]);

        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance');

        try {
            $subject->unsetAll(['a']);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }
}
