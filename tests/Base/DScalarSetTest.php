<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DScalarSet;
use Veelkoov\Debris\Exception\ChangingImmutableException;
use Veelkoov\Debris\Exception\EmptyCollectionException;

/**
 * @internal
 */
#[CoversClass(DScalarSet::class)]
#[UsesClass(ChangingImmutableException::class)]
final class DScalarSetTest extends TestCase
{
    #[Test]
    public function deduplicationWorks(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';
        $d = 'd';
        $e = 'e';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut([$a, $a, $b]);
        self::assertEqualsCanonicalizing([$a, $b], $subject->toArray());

        $subject->add($c);
        $subject->add($c);
        self::assertEqualsCanonicalizing([$a, $b, $c], $subject->toArray());

        $subject->addAll([$a, $d]);
        self::assertEqualsCanonicalizing([$a, $b, $c, $d], $subject->toArray());

        $subject->remove($d);
        $subject->remove($d);
        self::assertEqualsCanonicalizing([$a, $b, $c], $subject->toArray());

        $subject->removeAll([$a, $c, $e]);
        self::assertEqualsCanonicalizing([$b], $subject->toArray());
    }

    #[Test]
    public function immutabilityWorks(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut([$a]);

        $subject->add($b);
        $subject->remove($a);
        self::assertEqualsCanonicalizing([$b], $subject->toArray());

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

        self::assertEqualsCanonicalizing([$b], $subject->toArray());
    }

    #[Test]
    public function isEmpty_works(): void
    {
        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut();
        self::assertTrue($subject->isEmpty());

        $subject->add('item');
        self::assertFalse($subject->isEmpty());
    }

    #[Test]
    public function isNotEmpty_works(): void
    {
        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut();
        self::assertFalse($subject->isNotEmpty());

        $subject->add('item');
        self::assertTrue($subject->isNotEmpty());
    }

    #[Test]
    public function count_works(): void
    {
        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut();
        self::assertSame(0, $subject->count());

        $subject->add('item');
        self::assertSame(1, $subject->count());
    }

    #[Test]
    public function add_works(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut();

        $subject->add($a, $b);
        self::assertEqualsCanonicalizing([$b, $a], $subject->toArray());
    }

    #[Test]
    public function addAll_works(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut();

        $subject->addAll([$a, $b]);
        self::assertEqualsCanonicalizing([$b, $a], $subject->toArray());
    }

    #[Test]
    public function plusAll_works(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';
        $d = 'd';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut([$a, $b]);

        $result = $subject->plusAll([$c, $d]);
        self::assertEqualsCanonicalizing([$b, $a, $d, $c], $result->toArray());
    }

    #[Test]
    public function plus_works(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';
        $d = 'd';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut([$a, $b]);

        $result = $subject->plus($c, $d);
        self::assertEqualsCanonicalizing([$b, $a, $d, $c], $result->toArray());
    }

    #[Test]
    public function contains_works(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::of($a);

        self::assertTrue($subject->contains($a));
        self::assertFalse($subject->contains($b));
    }

    #[Test]
    public function max_throwsOnEmpty(): void
    {
        /** @var DScalarSet<string> $subject */
        $subject = new DScalarSet();

        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('Cannot find max() of an empty set.');
        $subject->max();
    }

    #[Test]
    public function max_worksWithoutCallable(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::of($a, $b);

        self::assertSame($b, $subject->max());
    }

    #[Test]
    public function max_worksWithCallable(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::of($a, $b);

        $result = $subject->max(static fn (string $item) => $item.$item);

        self::assertSame('bb', $result);
    }

    #[Test]
    public function filter_works(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::of($a, $b, $c);

        $result = $subject->filter(static fn (string $item) => $item >= 'b');

        self::assertEqualsCanonicalizing([$b, $c], $result->toArray());
    }

    #[Test]
    public function jsonSerialize_works(): void
    {
        $a = 'a';
        $b = 'b';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::of($a, $b);

        self::assertSame('["a","b"]', json_encode($subject));
    }

    #[Test]
    public function getIterator_works(): void
    {
        $a = 'a';
        $b = 'b';
        $c = 'c';

        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::of($a, $b, $c);

        self::assertSame([$a, $b, $c], [...$subject]);
    }
}
