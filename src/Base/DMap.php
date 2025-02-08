<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\DMapKey;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;
use Veelkoov\Debris\Base\Internal\DPair;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 */
class DMap implements \JsonSerializable
{
    /**
     * @var \SplObjectStorage<DMapKey<K>, V>
     */
    protected \SplObjectStorage $items;

    // TODO private bool $frozen = true;

    /**
     * @var DMapKeyMapper<K>
     */
    protected readonly DMapKeyMapper $mappedKeys;

    /**
     * @param iterable<K, V>|self<K, V> $items
     */
    final public function __construct(iterable|self $items = [])
    {
        $this->items = new \SplObjectStorage();
        $this->mappedKeys = new DMapKeyMapper();

        if ($items instanceof self) {
            foreach ($items->getPairsArray() as $pair) {
                $this->set($pair->key, $pair->value);
            }
        } else {
            foreach ($items as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    public function isEmpty(): bool
    {
        return 0 === $this->items->count();
    }

    public function isNotEmpty(): bool
    {
        return 0 !== $this->items->count();
    }

    public function count(): int
    {
        return $this->items->count();
    }

    /**
     * @param K $key
     * @param V $value
     *
     * @return $this
     */
    public function set(mixed $key, mixed $value): static
    {
        $this->items[$this->mappedKeys->get($key)] = $value;

        return $this;
    }

    /**
     * @param K $key
     *
     * @return V
     */
    public function get(mixed $key): mixed
    {
        return $this->items[$this->mappedKeys->get($key)];
    }

    /**
     * @param K             $key
     * @param callable(): V $newValueFunction
     *
     * @return V
     */
    public function getOrSet(mixed $key, callable|\Closure $newValueFunction): mixed
    {
        if (!$this->hasKey($key)) {
            $this->set($key, $newValueFunction());
        }

        return $this->get($key);
    }

    /**
     * @template T
     *
     * @param K                 $key
     * @param (callable(): T)|T $default
     *
     * @return T|V
     */
    public function getOrDefault(mixed $key, mixed $default): mixed
    {
        if (!$this->hasKey($key)) {
            return $this->get($key);
        }

        return \is_callable($default) ? $default() : $default;
    }

    /**
     * @param V $value
     */
    public function contains(mixed $value): bool
    {
        foreach ($this->items as $key) {
            if ($key === $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param K $key
     */
    public function hasKey(mixed $key): bool
    {
        return $this->items->contains($this->mappedKeys->get($key));
    }

    /**
     * @param callable(K, V): bool $function
     */
    public function filter(callable $function): static
    {
        $result = new static();

        foreach ($this->getKeysArray() as $key) {
            $value = $this->get($key);

            if ($function($key, $value)) {
                $result->set($key, $value);
            }
        }

        return $result;
    }

    /**
     * @param callable(V): bool $function
     */
    public function filterValues(callable $function): static
    {
        return $this->filter(static fn (mixed $key, mixed $value) => $function($value));
    }

    /**
     * @template NewV of object|scalar|null
     * @template NewK of object|scalar|null
     *
     * @param callable(K, V): DPair<NewK, NewV> $function
     *
     * @return self<NewK, NewV>
     */
    public function map(callable $function): self
    {
        $result = new self();

        foreach ($this->getPairsArray() as $pair) {
            $pair = $function($pair->key, $pair->value);
            $result->set($pair->key, $pair->value);
        }

        return $result;
    }

    /**
     * @template NewV of object|scalar|null
     *
     * @param callable(V): NewV $function
     *
     * @return self<K, NewV>
     */
    public function mapValues(callable $function): self
    {
        return $this->map(static fn (mixed $key, mixed $value) => new DPair($key, $function($value)));
    }

    /**
     * @return list<K>
     */
    public function getKeysArray(): array
    {
        $result = [];

        foreach ($this->items as $key) {
            $result[] = $key->key;
        }

        return $result;
    }

    /**
     * @return list<V>
     */
    public function getValuesArray(): array
    {
        $result = [];

        foreach ($this->items as $key) {
            $result[] = $this->items[$key];
        }

        return $result;
    }

    /**
     * @return DSet<K>
     */
    public function getKeys(): DSet
    {
        return new DSet($this->getKeysArray());
    }

    /**
     * @return DList<V>
     */
    public function getValues(): DList
    {
        return new DList($this->getValuesArray());
    }

    /**
     * @return list<DPair<K, V>>
     */
    public function getPairsArray(): array
    {
        $result = [];

        foreach ($this->items as $wrappedKey) {
            $result[] = new DPair($wrappedKey->key, $this->items[$wrappedKey]);
        }

        return $result;
    }

    /**
     * @param iterable<V>    $input
     * @param callable(V): K $valueToKeyFunction
     */
    public static function fromValues(iterable $input, callable $valueToKeyFunction): static
    {
        $result = new static();

        foreach ($input as $value) {
            $key = static::enforceKeyType($valueToKeyFunction($value));
            $value = static::enforceValueType($value);

            $result->set($key, $value);
        }

        return $result;
    }

    /**
     * @param iterable<mixed>    $input
     * @param int|literal-string $keyKey
     * @param int|literal-string $valueKey
     */
    public static function fromRows(iterable $input, int|string $keyKey, int|string $valueKey): static
    {
        $result = new static();

        foreach ($input as $row) {
            /** @phpstan-ignore argument.type */
            $key = static::enforceIsArrayAndKeyExistsGetKey($row, $keyKey);

            /** @phpstan-ignore argument.type */
            $value = static::enforceIsArrayAndKeyExistsGetKey($row, $valueKey);

            /** @phpstan-ignore argument.type */
            $key = static::enforceKeyType($key);

            /** @phpstan-ignore argument.type */
            $value = static::enforceValueType($value);

            $result->set($key, $value);
        }

        return $result;
    }

    /**
     * @param K $key
     *
     * @return $this
     */
    public function removeKey(mixed $key): static
    {
        $this->items->detach($this->mappedKeys->get($key));

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return $this->getPairsArray();
    }

    /**
     * @return self<V, K>
     */
    public function flip(): self
    {
        $result = new self();

        foreach ($this->getPairsArray() as $pair) {
            $result->set($pair->value, $pair->key);
        }

        return $result;
    }

    /**
     * @param callable(V, V): int $comparator
     */
    public function sorted(callable $comparator, bool $reverse = false): static
    {
        $times = $reverse ? -1 : 1;

        $pairs = $this->getPairsArray();
        usort($pairs, static fn (DPair $pair1, DPair $pair2): int => $times * $comparator($pair1->value, $pair2->value));

        $result = new static();

        foreach ($pairs as $pair) {
            $result->set($pair->key, $pair->value);
        }

        return $result;
    }

    /**
     * @param K $key
     *
     * @return K
     */
    protected static function enforceKeyType(mixed $key): mixed
    {
        return $key;
    }

    /**
     * @param V $value
     *
     * @return V
     */
    protected static function enforceValueType(mixed $value): mixed
    {
        return $value;
    }

    /**
     * @param array<mixed>|\ArrayAccess<mixed, mixed> $input
     */
    protected static function enforceIsArrayAndKeyExistsGetKey(array|\ArrayAccess $input, int|string $key): mixed
    {
        return $input[$key];
    }
}
