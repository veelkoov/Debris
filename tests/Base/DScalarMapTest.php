<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DScalarMap;

/**
 * @internal
 */
#[CoversClass(DScalarMap::class)]
final class DScalarMapTest extends TestCase
{
    #[Test]
    public function __construct_preservesKeysAndValues(): void
    {
        $input = [
            'a' => 1,
            'b' => 2,
        ];

        /** @var DScalarMap<string, int> $subject */
        $subject = new DScalarMap($input);

        self::assertEqualsCanonicalizing($input, $subject->toArray());
    }

    #[Test]
    public function fromRows_works(): void
    {
        $input = [
            [1 => 'abc', 'def' => 2, 'ghi' => 'jkl', 3 => 4],
            [1 => 'ABC', 'def' => 5, 'ghi' => 'JKL', 3 => 6],
        ];

        /** @var DScalarMap<string, string> $subject */
        $subject = DScalarMap::fromRows($input, 1, 'ghi');
        self::assertEqualsCanonicalizing([
            'abc' => 'jkl',
            'ABC' => 'JKL',
        ], $subject->toArray());

        /** @var DScalarMap<string, int> $subject */
        $subject = DScalarMap::fromRows($input, 'ghi', 3);
        self::assertEqualsCanonicalizing([
            'jkl' => 4,
            'JKL' => 6,
        ], $subject->toArray());
    }
}
