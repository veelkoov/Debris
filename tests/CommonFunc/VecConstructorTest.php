<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\ChangingImmutableException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Vec;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(Freezer::class)]
final class VecConstructorTest extends TestCase
{
    #[Test]
    #[DataProvider('provideConstructCases')]
    public function construct(callable $subjectFactory, callable $test, ?string $exceptionClass): void
    {
        if (null !== $exceptionClass) {
            self::expectException($exceptionClass); // @phpstan-ignore argument.type
        }

        self::assertNotNull($test($subjectFactory()));
    }

    public static function provideConstructCases(): iterable
    {
        $add100 = static fn (Vec $subject) => $subject->add(100);

        return [
            [static fn () => new DVec(frozen: false), $add100, null],
            [static fn () => new DVec(frozen: true), $add100, ChangingImmutableException::class],
        ];
    }
}
