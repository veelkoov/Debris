<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

/**
 * @template K of object|scalar|null
 *
 * @internal
 */
final class DMapKeyMapper
{
    /**
     * @var \SplObjectStorage<object, DMapKey<object>>
     */
    private \SplObjectStorage $wrappedObjectKeys;

    /**
     * @var array<int|string, DMapKey<int|string>>
     */
    private array $wrappedArrayKeys = [];

    /**
     * @var array<int, list<DMapKey<null|bool|float>>>
     */
    private array $wrappedScalarKeys = [];

    public function __construct()
    {
        $this->wrappedObjectKeys = new \SplObjectStorage();
    }

    /**
     * @param K $key
     *
     * @return DMapKey<K>
     */
    public function get(mixed $key): DMapKey
    {
        if (\is_object($key)) {
            return $this->wrappedObjectKeys[$key] ??= new DMapKey($key); // @phpstan-ignore return.type (FIXME)
        } if (\is_string($key) || \is_int($key)) {
            return $this->wrappedArrayKeys[$key] ??= new DMapKey($key); // @phpstan-ignore return.type (FIXME)
        }

        $intKey = (int) $key;

        foreach (($this->wrappedScalarKeys[$intKey] ??= []) as $wrappedKey) {
            if ($wrappedKey->key === $key) {
                return $wrappedKey; // @phpstan-ignore return.type (FIXME)
            }
        }

        $wrappedKey = new DMapKey($key);
        $this->wrappedScalarKeys[$intKey][] = $wrappedKey;

        return $wrappedKey; // @phpstan-ignore return.type (FIXME)
    }
}
