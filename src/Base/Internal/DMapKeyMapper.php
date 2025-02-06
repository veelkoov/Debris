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
     * @var array<int|string, DMapKey<int|string>>
     */
    private array $wrappedArrayKeys = [];

    /**
     * @var array<int, list<DMapKey<null|bool|float>>>
     */
    private array $wrappedScalarKeys = [];

    /**
     * @template T of K
     *
     * @param T $key
     *
     * @return ($key is object ? object : DMapKey<K>)
     */
    public function get(mixed $key): object
    {
        if (\is_object($key)) {
            return $key;
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
