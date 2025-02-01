<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\ChangingImmutableException;

/**
 * @internal
 */
#[CoversClass(ChangingImmutableException::class)]
final class ChangingImmutableExceptionTest extends TestCase
{
    #[Test]
    public function messageIsRight(): void
    {
        $subject = new ChangingImmutableException(__CLASS__);

        self::assertSame('Tried to modify immutable Veelkoov\Debris\Tests\Exception\ChangingImmutableExceptionTest', $subject->getMessage());
    }
}
