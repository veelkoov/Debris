<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Typehinting;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Maps\NullBoolToInt;

/**
 * @internal
 */
#[CoversNothing]
final class MapGetTest extends TestCase
{
    #[Test]
    public function get(): void
    {
        self::expectNotToPerformAssertions();

        $subject = new NullBoolToInt();

        self::requireInt($subject->getOrDefaultOf(null, 0));
        self::requireIntOrBool($subject->getOrDefaultOf(null, false));
    }

    private static function requireInt(int $input): void {}

    private static function requireIntOrBool(bool|int $input): void {}
}
