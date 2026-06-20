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

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DSet::class)]
#[CoversClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class IterationTest extends TestCase
{
    #[Test]
    public function DVec_foreachWorks(): void
    {
        $values = ['a', 1, new \stdClass(), false];
        $subject = new DVec($values);

        $resultValues = [];
        $resultKeys = [];

        foreach ($subject as $value) {
            $resultValues[] = $value;
        }

        foreach ($subject as $key => $_) {
            $resultKeys[] = $key;
        }

        self::assertSame($values, $resultValues);
        self::assertSame([0, 1, 2, 3], $resultKeys);
    }

    #[Test]
    public function DSet_foreachWorks(): void
    {
        $values = ['a', 1, new \stdClass(), false];
        $subject = new DSet($values);

        $resultValues = [];
        $resultKeys = [];

        foreach ($subject as $value) {
            $resultValues[] = $value;
        }

        foreach ($subject as $key => $_) {
            $resultKeys[] = $key;
        }

        self::assertSame($values, $resultValues);
        self::assertSame([0, 1, 2, 3], $resultKeys);
    }

    #[Test]
    public function DMap_foreachWorks(): void
    {
        $values = ['abc', 1, new \stdClass(), false];
        $keys = [-1, new \stdClass(), 0.45, 'xyz'];

        $subject = new DMap();
        foreach ($keys as $index => $key) {
            $subject->set($key, $values[$index]);
        }

        $resultValues = [];
        $resultKeys = [];

        foreach ($subject as $value) {
            $resultValues[] = $value;
        }

        foreach ($subject as $key => $_) {
            $resultKeys[] = $key;
        }

        self::assertSame($values, $resultValues);
        self::assertSame($keys, $resultKeys);
    }
}
