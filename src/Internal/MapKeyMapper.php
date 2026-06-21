<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Internal;

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
     * @var array<int, MapKey<covariant int>>
     */
    private array $wrappedIntKeys = [];

    /**
     * @var array<string, MapKey<covariant string>>
     */
    private array $wrappedStringKeys = [];

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
        }

        if (\is_int($key)) {
            return $this->wrappedIntKeys[$key] ??= new MapKey($key); // @phpstan-ignore return.type (FIXME)
        }

        if (\is_string($key)) {
            return $this->wrappedStringKeys[$key] ??= new MapKey($key); // @phpstan-ignore return.type (FIXME)
        }

        $keyIntRepr = (int) $key;

        foreach (($this->wrappedScalarKeys[$keyIntRepr] ??= []) as $wrappedKey) {
            if ($wrappedKey->key === $key) {
                return $wrappedKey; // @phpstan-ignore return.type (FIXME)
            }
        }

        $wrappedKey = new MapKey($key);
        $this->wrappedScalarKeys[$keyIntRepr][] = $wrappedKey;

        return $wrappedKey; // @phpstan-ignore return.type (FIXME)
    }
}
