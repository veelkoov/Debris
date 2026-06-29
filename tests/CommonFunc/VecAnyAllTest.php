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
final class VecAnyAllTest extends TestCase
{
    #[Test]
    public function _any(): void
    {
        $subject = new DVec([1, 2, 3]);

        self::assertTrue($subject->any(static fn (int $value) => $value > 2));
        self::assertFalse($subject->any(static fn (int $value) => $value > 3));
    }

    #[Test]
    public function all(): void
    {
        $subject = new DVec([1, 2, 3]);

        self::assertTrue($subject->all(static fn (int $value) => $value > 0));
        self::assertFalse($subject->all(static fn (int $value) => $value > 1));
    }
}
