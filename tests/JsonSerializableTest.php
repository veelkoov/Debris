<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\DMap;
use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;
use Veelkoov\Debris\Base\Internal\DPair;

/**
 * @internal
 */
#[CoversClass(DList::class)]
#[CoversClass(DMap::class)]
#[CoversClass(DSet::class)]
#[CoversClass(DPair::class)]
#[UsesClass(DMapKeyMapper::class)]
final class JsonSerializableTest extends TestCase
{
    #[Test]
    public function DPair_jsonSerialize(): void
    {
        self::assertSame('["a",1]', json_encode(new DPair('a', 1)));
    }

    #[Test]
    public function DList_jsonSerialize(): void
    {
        self::assertSame('["a","b"]', json_encode(new DList(['a', 'b'])));
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
