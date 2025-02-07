<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

/**
 * @internal
 */
final class DMapKeyMapper
{
    /**
     * @var \SplObjectStorage<object, DMapKey>
     */
    private \SplObjectStorage $wrappedObjectKeys;

    /**
     * @var array<int|string, DMapKey>
     */
    private array $wrappedArrayKeys = [];

    /**
     * @var array<int, list<DMapKey>>
     */
    private array $wrappedScalarKeys = [];

    public function __construct()
    {
        $this->wrappedObjectKeys = new \SplObjectStorage();
    }

    /**
     * @param null|object|scalar $key
     */
    public function get(mixed $key): DMapKey
    {
        if (\is_object($key)) {
            return $this->wrappedObjectKeys[$key] ??= new DMapKey($key);
        } if (\is_string($key) || \is_int($key)) {
            return $this->wrappedArrayKeys[$key] ??= new DMapKey($key);
        }

        $intKey = (int) $key;

        foreach (($this->wrappedScalarKeys[$intKey] ??= []) as $wrappedKey) {
            if ($wrappedKey->key === $key) {
                return $wrappedKey;
            }
        }

        $wrappedKey = new DMapKey($key);
        $this->wrappedScalarKeys[$intKey][] = $wrappedKey;

        return $wrappedKey;
    }
}
