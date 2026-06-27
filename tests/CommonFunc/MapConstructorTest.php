<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Exception\ChangingImmutableException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\Base\DIntMap;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Base\SimpleKeyMapTrait;

/**
 * @internal
 */
#[CoversClass(DMap::class)]
#[CoversTrait(SimpleKeyMapTrait::class)]
#[UsesClass(ChangingImmutableException::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class MapConstructorTest extends TestCase
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
        $set100to200 = static fn (Map $subject) => $subject->set(100, 200);

        return [
            [static fn () => new DMap(frozen: false), $set100to200, null],
            [static fn () => new DMap(frozen: true), $set100to200, ChangingImmutableException::class],
            [static fn () => new DIntMap(frozen: false), $set100to200, null],
            [static fn () => new DIntMap(frozen: true), $set100to200, ChangingImmutableException::class],
        ];
    }
}
