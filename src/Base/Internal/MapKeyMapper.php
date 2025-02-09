<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

/**
 * @template K of object|scalar|null
 *
 * @internal
 */
final class MapKeyMapper
{
    /**
     * @var \SplObjectStorage<object, MapKey<object>>
     */
    private \SplObjectStorage $wrappedObjectKeys;

    /**
     * @var array<int|string, MapKey<int|string>>
     */
    private array $wrappedArrayKeys = [];

    /**
     * @var array<int, list<MapKey<null|bool|float>>>
     */
    private array $wrappedScalarKeys = [];

    public function __construct()
    {
        $this->wrappedObjectKeys = new \SplObjectStorage();
    }

    /**
     * @param K $key
     *
     * @return MapKey<K>
     */
    public function get(mixed $key): MapKey
    {
        if (\is_object($key)) {
            return $this->wrappedObjectKeys[$key] ??= new MapKey($key); // @phpstan-ignore return.type (FIXME)
        } if (\is_string($key) || \is_int($key)) {
            return $this->wrappedArrayKeys[$key] ??= new MapKey($key); // @phpstan-ignore return.type (FIXME)
        }

        $intKey = (int) $key;

        foreach (($this->wrappedScalarKeys[$intKey] ??= []) as $wrappedKey) {
            if ($wrappedKey->key === $key) {
                return $wrappedKey; // @phpstan-ignore return.type (FIXME)
            }
        }

        $wrappedKey = new MapKey($key);
        $this->wrappedScalarKeys[$intKey][] = $wrappedKey;

        return $wrappedKey; // @phpstan-ignore return.type (FIXME)
    }
}
