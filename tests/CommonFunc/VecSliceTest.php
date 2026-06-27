<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[UsesClass(Freezer::class)]
final class VecSliceTest extends TestCase
{
    #[Test]
    public function slice(): void
    {
        $subject = new DVec([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        self::assertSame([3, 4, 5, 6], $subject->slice(2, 4)->getValuesArray());
        self::assertSame([6, 7, 8], $subject->slice(-5, -2)->getValuesArray());
    }
}
