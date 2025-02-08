<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DStringMap;

/**
 * @extends DStringMap<int>
 */
class StringIntMap extends DStringMap
{
    public function sorted(?callable $comparator = null, bool $reverse = false): static
    {
        return parent::sorted($comparator ?? static fn (int $value1, int $value2) => $value1 - $value2, $reverse);
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
}
