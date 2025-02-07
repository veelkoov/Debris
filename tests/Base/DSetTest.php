<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\DMapKey;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;
use Veelkoov\Debris\Exception\ChangingImmutableException;
use Veelkoov\Debris\Exception\EmptyCollectionException;

/**
 * @internal
 */
#[CoversClass(DSet::class)]
#[UsesClass(DMap::class)]
#[UsesClass(DMapKey::class)]
#[UsesClass(DMapKeyMapper::class)]
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

    // #[Test] FIXME
    public function immutabilityWorks(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DSet<string> $subject */
        $subject = DSet::mut([$a]);

        $subject->add($b);
        $subject->remove($a);
        self::assertEqualsCanonicalizing([$b], $subject->getValuesArray());

        $subject = $subject->frozen();

        try {
            $subject->add($a);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }

        try {
            $subject->remove($b);
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }

        self::assertEqualsCanonicalizing([$b], $subject->getValuesArray());
    }

    #[Test]
    public function isEmpty_works(): void
    {
        /** @var DSet<string> $subject */
        $subject = DSet::mut();
        self::assertTrue($subject->isEmpty());

        $subject->add('item');
        self::assertFalse($subject->isEmpty());
    }

    #[Test]
    public function isNotEmpty_works(): void
    {
        /** @var DSet<string> $subject */
        $subject = DSet::mut();
        self::assertFalse($subject->isNotEmpty());

        $subject->add('item');
        self::assertTrue($subject->isNotEmpty());
    }

    #[Test]
    public function count_works(): void
    {
        /** @var DSet<string> $subject */
        $subject = DSet::mut();
        self::assertSame(0, $subject->count());

        $subject->add('item');
        self::assertSame(1, $subject->count());
    }

    #[Test]
    public function add_works(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DSet<string> $subject */
        $subject = DSet::mut();

        $subject->add($a, $b);
        self::assertEqualsCanonicalizing([$b, $a], $subject->getValuesArray());
    }

    #[Test]
    public function addAll_works(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DSet<string> $subject */
        $subject = DSet::mut();

        $subject->addAll([$a, $b]);
        self::assertEqualsCanonicalizing([$b, $a], $subject->getValuesArray());
    }

    #[Test]
    public function plusAll_works(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';
        $d = 'd';

        /** @var DSet<string> $subject */
        $subject = DSet::mut([$a, $b]);

        $result = $subject->plusAll([$c, $d]);
        self::assertEqualsCanonicalizing([$b, $a, $d, $c], $result->getValuesArray());
    }

    #[Test]
    public function plus_works(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';
        $d = 'd';

        /** @var DSet<string> $subject */
        $subject = DSet::mut([$a, $b]);

        $result = $subject->plus($c, $d);
        self::assertEqualsCanonicalizing([$b, $a, $d, $c], $result->getValuesArray());
    }

    #[Test]
    public function contains_works(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DSet<string> $subject */
        $subject = DSet::of($a);

        self::assertTrue($subject->contains($a));
        self::assertFalse($subject->contains($b));
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
    public function filter_works(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';

        /** @var DSet<string> $subject */
        $subject = DSet::of($a, $b, $c);

        $result = $subject->filter(static fn (string $item) => $item >= 'b');

        self::assertEqualsCanonicalizing([$b, $c], $result->getValuesArray());
    }

    #[Test]
    public function jsonSerialize_works(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DSet<string> $subject */
        $subject = DSet::of($a, $b);

        self::assertSame('["a","b"]', json_encode($subject));
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
