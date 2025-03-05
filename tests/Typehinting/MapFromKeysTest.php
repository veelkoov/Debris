<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Typehinting;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Exception\MissingKeyException;
use Veelkoov\Debris\StringIntMap;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[CoversClass(StringIntMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class MapFromKeysTest extends TestCase
{
    #[Test]
    public function DMap_fromKeys(): void
    {
        $subject = DMap::fromKeys(['1', '2', '3', '4', '5'], static fn (string $value) => (int) $value);

        self::assertInstanceOf(DMap::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)

        // Tests stuff "works as expected"
        self::assertSame(1, $subject->get('1'));
        $subject->set('6', 6);
        self::assertSame(6, $subject->get('6'));

        try {
            $subject->get(6); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
            self::fail('Exception should have been thrown');
        } catch (MissingKeyException) {
            // Expected
        }

        $subject->set(7, 7); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
        $subject->set(7, '7'); // @phpstan-ignore argument.type,argument.type (TESTING EXPECTATIONS)
        $subject->set('7', 7);
        $subject->set('7', '7'); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }

    #[Test]
    public function StringIntMap_fromKeys(): void
    {
        $subject = StringIntMap::fromKeys(['1', '2', '3', '4', '5'], static fn (string $value) => (int) $value);

        self::assertInstanceOf(StringIntMap::class, $subject); // @phpstan-ignore staticMethod.alreadyNarrowedType (Paranoia)

        // Tests stuff "works as expected"
        self::assertSame(1, $subject->get('1'));
        $subject->set('6', 6);
        self::assertSame(6, $subject->get('6'));

        try {
            $subject->get(6); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
            self::fail('Exception should have been thrown');
        } catch (MissingKeyException) {
            // Expected
        }

        $subject->set(7, 7); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
        $subject->set(7, '7'); // @phpstan-ignore argument.type,argument.type (TESTING EXPECTATIONS)
        $subject->set('7', 7);
        $subject->set('7', '7'); // @phpstan-ignore argument.type (TESTING EXPECTATIONS)
    }
}
