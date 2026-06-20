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
use Veelkoov\Debris\Exception\ChangingImmutableException;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class AddSetTest extends TestCase
{
    #[Test]
    public function DVec_add(): void
    {
        $subject = new DVec([1]);

        $result = $subject->add(2)->add(1);

        self::assertSame($subject, $result, 'Result should be the modified, original instance');
        self::assertSame([1, 2, 1], $result->getValuesArray());
    }

    #[Test]
    public function DSet_add(): void
    {
        $subject = new DSet([1]);

        $result = $subject->add(2)->add(1);

        self::assertSame($subject, $result, 'Result should be the modified, original instance');
        self::assertSame([1, 2], $result->getValuesArray());
    }

    #[Test]
    public function DMap_set(): void
    {
        $subject = new DMap(['a' => 1]);

        $result = $subject->set('b', 2)->set('a', 3);

        self::assertSame($subject, $result, 'Result should be the modified, original instance');
        self::assertSame(['a', 'b'], $result->getKeysArray());
        self::assertSame([3, 2], $result->getValuesArray());
    }

    #[Test]
    public function DVec_add_onFrozen(): void
    {
        $subject = new DVec([1]);

        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance');

        try {
            $subject->add(2);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }

    #[Test]
    public function DSet_add_onFrozen(): void
    {
        $subject = new DSet([1]);

        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance');

        try {
            $subject->add(2);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }

    #[Test]
    public function DMap_set_onFrozen(): void
    {
        $subject = new DMap(['a' => 1]);

        self::assertSame($subject, $subject->freeze(), 'Freeze should return the original instance');

        try {
            $subject->set('b', 2);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }
    }
}
