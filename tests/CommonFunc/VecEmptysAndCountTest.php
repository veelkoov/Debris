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
final class VecEmptysAndCountTest extends TestCase
{
    #[Test]
    public function _isEmpty(): void
    {
        self::assertTrue((new DVec())->isEmpty());
        self::assertFalse((new DVec([1]))->isEmpty());
    }

    #[Test]
    public function isNotEmpty(): void
    {
        self::assertFalse((new DVec())->isNotEmpty());
        self::assertTrue((new DVec([1]))->isNotEmpty());
    }

    #[Test]
    public function _count(): void
    {
        self::assertCount(0, new DVec());
        self::assertCount(2, new DVec([1, 2]));
    }
}
