<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests\CommonFunc;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Internal\MapKeyMapper;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Maps\Pair;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Vecs\Base\DVec;

/**
 * @internal
 */
#[CoversClass(DVec::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[CoversClass(Pair::class)]
#[UsesClass(Freezer::class)]
#[UsesClass(MapKeyMapper::class)]
final class JsonSerializableTest extends TestCase
{
    #[Test]
    public function DPair_jsonSerialize(): void
    {
        self::assertSame('["a",1]', json_encode(new Pair('a', 1)));
    }

    #[Test]
    public function DVec_jsonSerialize(): void
    {
        self::assertSame('["a","b"]', json_encode(new DVec(['a', 'b'])));
    }

    #[Test]
    public function DSet_jsonSerialize(): void
    {
        self::assertSame('["a","b"]', json_encode(new DSet(['a', 'b'])));
    }

    #[Test]
    public function DMap_jsonSerialize(): void
    {
        self::assertSame('[["a",1],["b",2]]', json_encode(new DMap(['a' => 1, 'b' => 2])));
    }
}
