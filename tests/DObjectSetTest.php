<?php

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;
use Veelkoov\Debris\DObjectSet;

#[CoversClass(DObjectSet::class)]
class DObjectSetTest extends TestCase
{
    #[Test]
    public function deduplicationWorks(): void
    {
        $a = new stdClass();
        $b = new stdClass();
        $c = new stdClass();
        $d = new stdClass();
        $e = new stdClass();

        /** @var DObjectSet<stdClass> $subject */
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
}
