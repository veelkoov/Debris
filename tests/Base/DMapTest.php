<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Internal\DMapKey;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[UsesClass(DMapKey::class)]
#[UsesClass(DMapKeyMapper::class)]
final class DMapTest extends TestCase
{
    // #[Test] FIXME
    public function __construct_preservesKeysAndValues(): void
    {
        $input = [
            'a' => 1,
            'b' => 2,
        ];

        /** @var DMap<string, int> $subject */
        $subject = new DMap($input);

        self::assertEqualsCanonicalizing($input, $subject->get()); // @phpstan-ignore arguments.count (FIXME)
    }

    #[Test]
    public function properlyHandlesMultipleTypes(): void
    {
        /**
         * @var DMap<null|object|scalar, int> $subject
         */
        $subject = new DMap();

        $testKeys = [
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

        foreach ($testKeys as $value => $key) {
            $subject->set($key, $value);
        }

        $keys = $subject->getKeysArray();
        self::assertSameSize($testKeys, $keys);

        foreach ($testKeys as $value => $key) {
            self::assertSame($key, $keys[$value]);
            self::assertSame($value, $subject->get($key));
        }
    }

    // #[Test] FIXME
    public function fromRows_works(): void
    {
        $input = [
            [1 => 'abc', 'def' => 2, 'ghi' => 'jkl', 3 => 4],
            [1 => 'ABC', 'def' => 5, 'ghi' => 'JKL', 3 => 6],
        ];

        /** @var DMap<string, string> $subject */
        $subject = DMap::fromRows($input, 1, 'ghi');
        self::assertEqualsCanonicalizing([
            'abc' => 'jkl',
            'ABC' => 'JKL',
        ], $subject->toArray()); /** @phpstan-ignore method.notFound (FIXME) */

        /** @var DMap<string, int> $subject */
        $subject = DMap::fromRows($input, 'ghi', 3);
        self::assertEqualsCanonicalizing([
            'jkl' => 4,
            'JKL' => 6,
        ], $subject->toArray()); // @phpstan-ignore method.notFound (FIXME)
    }
}
