<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps\Base;

use Veelkoov\Debris\Enforce\StringKeysTrait;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Sets\StringSet;

/**
 * @template V of object|scalar|null
 *
 * @implements Map<string, V>
 */
class DStringMap implements Map
{
    /**
     * @use SimpleKeyMapTrait<string, V>
     */
    use SimpleKeyMapTrait;
    use StringKeysTrait;

    #[\Override]
    public function getKeys(): StringSet
    {
        return new StringSet(array_keys($this->items));
    }
}
