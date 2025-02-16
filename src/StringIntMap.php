<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Internal\Pair;

/**
 * @extends DStringMap<int>
 */
class StringIntMap extends DStringMap
{
    #[\Override]
    public function sorted(?callable $comparator = null, bool $reverse = false): static
    {
        return parent::sorted($comparator ?? self::comparator(...), $reverse);
    }

    #[\Override]
    protected static function enforceKeyType(mixed $key): string // TODO: Implement use in all ADD
    {
        return $key;
    }

    #[\Override]
    protected static function enforceValueType(mixed $value): int // TODO: Implement use in all ADD
    {
        return $value;
    }

    /**
     * @param Pair<string, int> $pair1
     * @param Pair<string, int> $pair2
     */
    private static function comparator(Pair $pair1, Pair $pair2): int
    {
        return $pair1->value - $pair2->value;
    }
}
