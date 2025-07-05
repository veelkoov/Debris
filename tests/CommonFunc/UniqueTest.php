<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\Internal\Freezer;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[UsesClass(Freezer::class)]
final class UniqueTest extends TestCase
{
    #[Test]
    public function DList_unique(): void
    {
        $subject = new DList([1, 2, 3, 2, 1, 3, 4, 3, 2]);

        $result = $subject->unique();

        self::assertEqualsCanonicalizing([1, 2, 3, 4], $result->getValuesArray());
    }
}
