<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\ChangingImmutableException;
use Veelkoov\Debris\DObjectSet;

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
}
