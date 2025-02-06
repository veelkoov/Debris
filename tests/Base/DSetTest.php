<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\DMapKey;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DSet::class)]
#[UsesClass(DMap::class)]
#[UsesClass(DMapKey::class)]
#[UsesClass(DMapKeyMapper::class)]
final class DSetTest extends TestCase
{
    #[Test]
    public function properlyHandlesMultipleTypes(): void
    {
        $testValues = [
            new \stdClass(),
            new \stdClass(),
            -2,
            -1,
            0,
            1,
            2,
            -2.4,
            -2.3,
            -1.3,
            -1.2,
            -0.2,
            -0.1,
            0.0,
            0.1,
            0.2,
            1.2,
            1.3,
            2.3,
            2.4,
            true,
            false,
            null,
        ];

        /**
         * @var DSet<null|object|scalar> $subject
         */
        $subject = new DSet($testValues);

        $result = $subject->getValues();

        self::assertSameSize($testValues, $result);

        foreach ($testValues as $value) {
            self::assertContains($value, $result);
        }
    }
}
