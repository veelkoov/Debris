<?php

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Veelkoov\Debris\DScalarSet;
use PHPUnit\Framework\TestCase;

#[CoversClass(DScalarSet::class)]
class DScalarSetTest extends TestCase
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
}
