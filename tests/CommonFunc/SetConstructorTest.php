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
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Set;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Sets\IntSet;

/**
 * @internal
 */
#[CoversClass(DIntOrStringSet::class)]
#[CoversClass(DSet::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(DMap::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class SetConstructorTest extends TestCase
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
        $add100 = static fn (Set $subject) => $subject->add(100);

        return [
            [static fn () => new DSet(frozen: false), $add100, null],
            [static fn () => new DSet(frozen: true), $add100, ChangingImmutableException::class],
            [static fn () => new IntSet(frozen: false), $add100, null],
            [static fn () => new IntSet(frozen: true), $add100, ChangingImmutableException::class],
        ];
    }
}
