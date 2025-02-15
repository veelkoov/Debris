<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKey;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Exception\EmptyCollectionException;

/**
 * @internal
 */
#[CoversClass(DSet::class)]
#[UsesClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKey::class)]
#[UsesClass(MapKeyMapper::class)]
final class DSetTest extends TestCase
{
    #[Test]
    public function properlyHandlesMultipleTypes(): void
    {
        $testValues = [
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

        /**
         * @var DSet<null|object|scalar> $subject
         */
        $subject = new DSet($testValues);

        $result = $subject->getValuesArray();

        self::assertSameSize($testValues, $result);

        foreach ($testValues as $value) {
            self::assertContains($value, $result);
        }
    }

    #[Test]
    public function deduplicationWorks(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';
        $d = 'd';
        $e = 'e';

        /** @var DSet<string> $subject */
        $subject = DSet::mut([$a, $a, $b]);
        self::assertEqualsCanonicalizing([$a, $b], $subject->getValuesArray());

        $subject->add($c);
        $subject->add($c);
        self::assertEqualsCanonicalizing([$a, $b, $c], $subject->getValuesArray());

        $subject->addAll([$a, $d]);
        self::assertEqualsCanonicalizing([$a, $b, $c, $d], $subject->getValuesArray());

        $subject->remove($d);
        $subject->remove($d);
        self::assertEqualsCanonicalizing([$a, $b, $c], $subject->getValuesArray());

        $subject->removeAll([$a, $c, $e]);
        self::assertEqualsCanonicalizing([$b], $subject->getValuesArray());
    }

    #[Test]
    public function max_throwsOnEmpty(): void
    {
        /** @var DSet<string> $subject */
        $subject = new DSet();

        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('Cannot find max() of an empty set.');
        $subject->max();
    }

    #[Test]
    public function max_worksWithoutCallable(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DSet<string> $subject */
        $subject = DSet::of($a, $b);

        self::assertSame($b, $subject->max());
    }

    #[Test]
    public function max_worksWithCallable(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DSet<string> $subject */
        $subject = DSet::of($a, $b);

        $result = $subject->max(static fn (string $item) => $item.$item);

        self::assertSame('bb', $result);
    }

    #[Test]
    public function getIterator_works(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';

        /** @var DSet<string> $subject */
        $subject = DSet::of($a, $b, $c);

        self::assertSame([$a, $b, $c], [...$subject]);
    }
}
