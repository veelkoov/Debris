<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps\Base;

use Veelkoov\Debris\Enforce\IntKeysTrait;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Sets\IntSet;

/**
 * @template V of object|scalar|null
 *
 * @implements Map<int, V>
 */
class DIntMap implements Map
{
    use IntKeysTrait;

    /**
     * @use SimpleKeyMapTrait<int, V>
     */
    use SimpleKeyMapTrait;

    public function getKeys(): IntSet
    {
        return new IntSet(array_keys($this->items));
    }
}
