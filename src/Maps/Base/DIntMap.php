<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps\Base;

use Veelkoov\Debris\Map;
use Veelkoov\Debris\Sets\IntSet;

/**
 * @template V of object|scalar|null
 *
 * @implements Map<int, V>
 */
class DIntMap implements Map
{
    /**
     * @use SimpleKeyMapTrait<int, V>
     */
    use SimpleKeyMapTrait;

    public function getKeys(): IntSet
    {
        return new IntSet(array_keys($this->items));
    }

    /**
     * @param int $key
     */
    protected static function enforceKeyType(mixed $key): int
    {
        return $key;
    }

    /**
     * @phpstan-assert-if-true int $key
     */
    protected static function isValidKey(mixed $key): bool
    {
        return \is_int($key);
    }
}
