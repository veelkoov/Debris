<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps\Base;

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

    public function getKeys(): StringSet
    {
        return new StringSet(array_keys($this->items));
    }

    /**
     * @param string $key
     */
    protected static function enforceKeyType(mixed $key): string
    {
        return $key;
    }

    /**
     * @phpstan-assert-if-true string $key
     */
    protected static function isValidKey(mixed $key): bool
    {
        return \is_string($key);
    }
}
