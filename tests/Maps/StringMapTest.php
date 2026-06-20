<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Maps;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\EveryMapTrait;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\SimpleKeyMapTrait;
use Veelkoov\Debris\Maps\StringToInt;

/**
 * @internal
 */
#[CoversTrait(SimpleKeyMapTrait::class)]
#[UsesTrait(EveryMapTrait::class)]
#[UsesClass(DStringMap::class)]
#[UsesClass(Freezer::class)]
final class StringMapTest extends TestCase
{
    public function testHasKeyChecksType(): void
    {
        $subject = (new StringToInt())->set('1', 1);

        self::assertTrue($subject->hasKey('1'));
        self::assertFalse($subject->hasKey(1)); // @phpstan-ignore argument.type (Testing)
    }
}
