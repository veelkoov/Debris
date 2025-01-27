<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\ChangingImmutableException;
use Veelkoov\Debris\DScalarSet;

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
        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut(['a', 'a', 'b', 'B']);
        self::assertEqualsCanonicalizing(['B', 'a', 'b'], $subject->toArray());

        $subject->add('c');
        $subject->add('c');
        self::assertEqualsCanonicalizing(['a', 'b', 'B', 'c'], $subject->toArray());

        $subject->addAll(['a', 'd']);
        self::assertEqualsCanonicalizing(['a', 'b', 'B', 'c', 'd'], $subject->toArray());

        $subject->remove('B');
        $subject->remove('B');
        self::assertEqualsCanonicalizing(['a', 'b', 'c', 'd'], $subject->toArray());

        $subject->removeAll(['a', 'c', 'e']);
        self::assertEqualsCanonicalizing(['b', 'd'], $subject->toArray());
    }

    #[Test]
    public function immutabilityWorks(): void
    {
        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut(['a']);

        $subject->add('b');
        $subject->remove('a');
        self::assertEqualsCanonicalizing(['b'], $subject->toArray());

        $subject = $subject->frozen();

        try {
            $subject->add('a');
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }

        try {
            $subject->remove('b');
            self::fail('Exception was excepted on the line above.');
        } catch (ChangingImmutableException) {
            // Expected
        }

        self::assertEqualsCanonicalizing(['b'], $subject->toArray());
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
    public function addAll_works(): void
    {
        /** @var DScalarSet<string> $subject */
        $subject = DScalarSet::mut();

        $subject->addAll(['a', 'b']);
        self::assertEqualsCanonicalizing(['b', 'a'], $subject->toArray());
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
}
