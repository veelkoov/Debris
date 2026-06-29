<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[UsesClass(Freezer::class)]
final class VarRandomTest extends TestCase
{
    #[Test]
    public function random(): void
    {
        $subject = new DVec(self::getTestList());

        $firstResult = $subject->random();
        for ($i = 0; $i < 100; ++$i) {
            $nextResult = $subject->random();
            if ($firstResult !== $nextResult) {
                break;
            }
        }

        self::assertNotSame($firstResult, $nextResult); // @phpstan-ignore variable.undefined (It will be assigned on first loop iteration)
    }

    #[Test]
    public function random_empty(): void
    {
        self::expectException(EmptyCollectionException::class);
        self::expectExceptionMessage('The list is empty.');

        (new DVec())->random();
    }

    /**
     * @return list<int>
     */
    private static function getTestList(): array
    {
        $input = range(1, 1000);

        self::assertSame(5, $input[5 - 1]);
        self::assertSame(995, $input[995 - 1]);

        return $input;
    }
}
