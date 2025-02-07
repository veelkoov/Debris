<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\Internal\DMapKey;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DMapKeyMapper::class)]
#[UsesClass(DMapKey::class)]
final class DMapKeyMapperTest extends TestCase
{
    #[Test]
    public function get(): void
    {
        $subject = new DMapKeyMapper();

        $testItems = [
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

        $keysFirstRetrieval = [];
        $keysSecondRetrieval = [];

        foreach ($testItems as $item) {
            $keysFirstRetrieval[] = $subject->get($item);
        }

        foreach ($testItems as $item) {
            $keysSecondRetrieval[] = $subject->get($item);
        }

        self::assertSameSize($testItems, $keysFirstRetrieval);
        self::assertSameSize($testItems, $keysSecondRetrieval);

        for ($i = 0, $iMax = \count($testItems); $i < $iMax; ++$i) {
            self::assertSame($keysFirstRetrieval[$i], $keysSecondRetrieval[$i]);

            self::assertSame($testItems[$i], $keysFirstRetrieval[$i]->key);
        }
    }
}
