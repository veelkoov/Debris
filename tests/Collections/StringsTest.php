<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Collections;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Collections\Strings;
use Veelkoov\Debris\Sets\StringSet;
use Veelkoov\Debris\Vecs\StringVec;

/**
 * @internal
 */
#[CoversNothing]
final class StringsTest extends TestCase
{
    private Strings $subject;

    public function testInterfaces(): void
    {
        $this->subject = new StringVec();
        self::assertTrue($this->subject->isEmpty());

        $this->subject = new StringSet();
        self::assertTrue($this->subject->isEmpty());
    }
}
