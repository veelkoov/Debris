<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\Base;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DObjectSet;
use Veelkoov\Debris\Exception\ChangingImmutableException;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Tests\DebrisTestsUtils\Sortable;

/**
 * @internal
 */
#[CoversClass(DObjectSet::class)]
#[UsesClass(ChangingImmutableException::class)]
final class DObjectSetTest extends TestCase
{
    #[Test]
    public function deduplicationWorks(): void
    {
        $a = new \stdClass();
        $b = new \stdClass();
        $c = new \stdClass();
        $d = new \stdClass();
        $e = new \stdClass();

        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::mut([$a, $a, $b]);
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
        $a = new \stdClass();
        $b = new \stdClass();

        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::mut([$a]);

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
        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::mut();
        self::assertTrue($subject->isEmpty());

        $subject->add(new \stdClass());
        self::assertFalse($subject->isEmpty());
    }

    #[Test]
    public function isNotEmpty_works(): void
    {
        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::mut();
        self::assertFalse($subject->isNotEmpty());

        $subject->add(new \stdClass());
        self::assertTrue($subject->isNotEmpty());
    }

    #[Test]
    public function count_works(): void
    {
        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::mut();
        self::assertSame(0, $subject->count());

        $subject->add(new \stdClass());
        self::assertSame(1, $subject->count());
    }

    #[Test]
    public function add_works(): void
    {
        $a = new \stdClass();
        $b = new \stdClass();

        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::mut();

        $subject->add($a, $b);
        self::assertEqualsCanonicalizing([$b, $a], $subject->toArray());
    }

    #[Test]
    public function addAll_works(): void
    {
        $a = new \stdClass();
        $b = new \stdClass();

        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::mut();

        $subject->addAll([$a, $b]);
        self::assertEqualsCanonicalizing([$b, $a], $subject->toArray());
    }

    #[Test]
    public function plusAll_works(): void
    {
        $a = new \stdClass();
        $b = new \stdClass();
        $c = new \stdClass();
        $d = new \stdClass();

        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::mut([$a, $b]);

        $result = $subject->plusAll([$c, $d]);
        self::assertEqualsCanonicalizing([$b, $a, $d, $c], $result->toArray());
    }

    #[Test]
    public function plus_works(): void
    {
        $a = new \stdClass();
        $b = new \stdClass();
        $c = new \stdClass();
        $d = new \stdClass();

        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::mut([$a, $b]);

        $result = $subject->plus($c, $d);
        self::assertEqualsCanonicalizing([$b, $a, $d, $c], $result->toArray());
    }

    #[Test]
    public function contains_works(): void
    {
        $a = new \stdClass();
        $b = new \stdClass();

        /** @var DObjectSet<\stdClass> $subject */
        $subject = DObjectSet::of($a);

        self::assertTrue($subject->contains($a));
        self::assertFalse($subject->contains($b));
    }

    #[Test]
    public function max_throwsOnEmpty(): void
    {
        /** @var DObjectSet<\stdClass> $subject */
        $subject = new DObjectSet();

        self::expectException(EmptyCollectionException::class);
        $subject->max();
    }

    #[Test]
    public function max_worksWithoutCallable(): void
    {
        $a = new Sortable('a');
        $b = new Sortable('b');

        /** @var DObjectSet<Sortable> $subject */
        $subject = DObjectSet::of($a, $b);

        self::assertSame($b, $subject->max());
    }

    #[Test]
    public function max_worksWithCallable(): void
    {
        $a = new Sortable('a');
        $b = new Sortable('b');

        /** @var DObjectSet<Sortable> $subject */
        $subject = DObjectSet::of($a, $b);

        $result = $subject->max(static fn (Sortable $item) => $item.$item);

        self::assertSame('bb', $result);
    }

    #[Test]
    public function filter_works(): void
    {
        $a = new Sortable('a');
        $b = new Sortable('b');
        $c = new Sortable('c');

        /** @var DObjectSet<Sortable> $subject */
        $subject = DObjectSet::of($a, $b, $c);

        $result = $subject->filter(static fn (Sortable $item) => $item >= 'b');

        self::assertEqualsCanonicalizing([$b, $c], $result->toArray());
    }

    #[Test]
    public function jsonSerialize_works(): void
    {
        $a = new Sortable('a');
        $b = new Sortable('b');

        /** @var DObjectSet<Sortable> $subject */
        $subject = DObjectSet::of($a, $b);

        self::assertSame('["a","b"]', json_encode($subject));
    }

    #[Test]
    public function getIterator_works(): void
    {
        $a = new Sortable('a');
        $b = new Sortable('b');
        $c = new Sortable('c');

        /** @var DObjectSet<Sortable> $subject */
        $subject = DObjectSet::of($a, $b, $c);

        self::assertSame([$a, $b, $c], [...$subject]);
    }
}
