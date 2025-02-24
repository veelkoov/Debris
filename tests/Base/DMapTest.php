<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKey;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKey::class)]
#[UsesClass(MapKeyMapper::class)]
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

    #[Test]
    public function flip(): void
    {
        $object = new \stdClass();
        $input = [10, -2, 'abc', $object, 0.123, false, 'abc', null, -2];

        $subject = new DMap($input);
        $result = $subject->flip();

        self::assertSame([0, 8, 6, 3, 4, 5, 7], $result->getValuesArray());
        self::assertSame([10, -2, 'abc', $object, 0.123, false, null], $result->getKeysArray());
    }

    #[Test]
    public function getOrSet_returnsExisting(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('existingValue', $subject->getOrSet('existingKey', static fn () => 'newValue'), 'Existing value should be returned');
    }

    #[Test]
    public function getOrSet_returnsMissing(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('newValue_1', $subject->getOrSet('missingKey', static fn () => 'newValue_1'), 'Default value should be returned');
        self::assertSame('newValue_1', $subject->getOrSet('missingKey', static fn () => 'newValue_2'), 'Map should have been updated');
    }

    #[Test]
    public function getOrDefault_returnsExisting(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('existingValue', $subject->getOrSet('existingKey', static fn () => 'newValue'), 'Existing value should be returned');
    }

    #[Test]
    public function getOrDefault_returnsMissing(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('newValue_1', $subject->getOrDefault('missingKey', static fn () => 'newValue_1'), 'Default value should be returned');
        self::assertSame('newValue_2', $subject->getOrSet('missingKey', static fn () => 'newValue_2'), 'Map should not have been updated');
    }
}
