<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\MissingKeyException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKey;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKey::class)]
#[UsesClass(MapKeyMapper::class)]
final class DMapTest extends TestCase
{
    #[Test]
    public function __isset__get(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertTrue(isset($subject->existingKey)); // @phpstan-ignore property.notFound (For Twig, etc., no support in code yet)
        self::assertSame('existingValue', $subject->existingKey);
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
    public function get_returnsExisting(): void
    {
        $subject = new DMap(['aKey' => 'aValue']);

        self::assertSame('aValue', $subject->get('aKey'));
    }

    #[Test]
    public function get_throwsOnMissingScalarKey(): void
    {
        $subject = new DMap(['aKey' => 'aValue']);

        self::expectException(MissingKeyException::class);
        self::expectExceptionMessage("Missing string key: 'missingKey'");
        $subject->get('missingKey');
    }

    #[Test]
    public function get_throwsOnMissingObjectKey(): void
    {
        $subject = new DMap(['aKey' => 'aValue']);

        self::expectException(MissingKeyException::class);
        self::expectExceptionMessage('Missing stdClass key');
        $subject->get(new \stdClass()); // @phpstan-ignore argument.type (Cannot construct with object keys)
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
    public function getOrSetAs_returnsExisting(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('existingValue', $subject->getOrSetAs('existingKey', 'newValue'), 'Existing value should be returned');
    }

    #[Test]
    public function getOrSetAs_returnsMissing(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('newValue_1', $subject->getOrSetAs('missingKey', 'newValue_1'), 'Default value should be returned');
        self::assertSame('newValue_1', $subject->getOrSetAs('missingKey', 'newValue_2'), 'Map should have been updated');
    }

    #[Test]
    public function getOrDefault_returnsExisting(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('existingValue', $subject->getOrDefault('existingKey', static fn () => 'newValue'), 'Existing value should be returned');
    }

    #[Test]
    public function getOrDefault_returnsMissing(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('newValue_1', $subject->getOrDefault('missingKey', static fn () => 'newValue_1'), 'Default value should be returned');
        self::assertSame('newValue_2', $subject->getOrSet('missingKey', static fn () => 'newValue_2'), 'Map should not have been updated');
    }

    #[Test]
    public function getOrDefaultOf_returnsExisting(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('existingValue', $subject->getOrDefaultOf('existingKey', 'newValue'), 'Existing value should be returned');
    }

    #[Test]
    public function getOrDefaultOf_returnsMissing(): void
    {
        $subject = new DMap(['existingKey' => 'existingValue']);

        self::assertSame('newValue_1', $subject->getOrDefaultOf('missingKey', 'newValue_1'), 'Default value should be returned');
        self::assertSame('newValue_2', $subject->getOrSetAs('missingKey', 'newValue_2'), 'Map should not have been updated');
    }
}
