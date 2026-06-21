<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base\Internal;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\ChangingImmutableException;
use Veelkoov\Debris\Internal\Freezer;

/**
 * @internal
 */
#[CoversClass(Freezer::class)]
#[UsesClass(ChangingImmutableException::class)]
final class FreezerTest extends TestCase
{
    #[Test]
    public function protectionWorks(): void
    {
        $subject = new Freezer($this, true);

        $this->expectExceptionWithTheRightMessage();
        $subject->protect();
    }

    #[Test]
    public function freezingWorks(): void
    {
        $subject = new Freezer($this, false);

        $subject->protect();
        $subject->freeze();

        $this->expectExceptionWithTheRightMessage();
        $subject->protect();
    }

    private function expectExceptionWithTheRightMessage(): void
    {
        self::expectException(ChangingImmutableException::class);
        self::expectExceptionMessage('Tried to modify immutable Veelkoov\Debris\Tests\Base\Internal\FreezerTest');
    }
}
