<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKey;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Base\Internal\Pair;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 */
class DMap implements \JsonSerializable
{
    /**
     * @var \SplObjectStorage<MapKey<K>, V>
     */
    protected \SplObjectStorage $items;

    protected readonly Freezer $freezer;

    /**
     * @var MapKeyMapper<K>
     */
    protected readonly MapKeyMapper $mappedKeys;

    /**
     * @param iterable<K, V>|self<K, V> $items
     */
    final public function __construct(iterable|self $items = [], bool $frozen = true)
    {
        $this->items = new \SplObjectStorage();
        $this->mappedKeys = new MapKeyMapper();
        $this->freezer = new Freezer($this, false);

        $this->setAll($items);

        if ($frozen) {
            $this->freezer->freeze();
        }
    }

    /**
     * @param iterable<K, V>|self<K, V> $items
     */
    public static function mut(iterable|self $items = []): static
    {
        return new static($items, frozen: false);
    }

    /**
     * @return $this
     */
    public function freeze(): static
    {
        $this->freezer->freeze();

        return $this;
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
        $this->freezer->protect();

        $this->items[$this->mappedKeys->get($key)] = $value;

        return $this;
    }

    /**
     * @param iterable<K, V>|self<K, V> $items
     *
     * @return $this
     */
    public function setAll(iterable|self $items): static
    {
        if ($items instanceof self) {
            foreach ($items->getPairsArray() as $pair) {
                $this->set($pair->key, $pair->value);
            }
        } else {
            foreach ($items as $key => $value) {
                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * @param K $key
     * @param V $value
     */
    public function plus(mixed $key, mixed $value): static
    {
        $result = new static($this, frozen: false);
        $result->set($key, $value);

        return $result->freeze();
    }

    /**
     * @param iterable<K, V>|self<K, V> $items
     *
     * @return $this
     */
    public function plusAll(iterable|self $items): static
    {
        $result = new static($this, frozen: false);

        if ($items instanceof self) {
            foreach ($items->getPairsArray() as $pair) {
                $result->set($pair->key, $pair->value);
            }
        } else {
            foreach ($items as $key => $value) {
                $result->set($key, $value);
            }
        }

        return $result->freeze();
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
            if ($this->items[$key] === $value) {
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
        $result = new static(frozen: false);

        foreach ($this->getKeysArray() as $key) {
            $value = $this->get($key);

            if ($function($key, $value)) {
                $result->set($key, $value);
            }
        }

        return $result->freeze();
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
     * @param callable(K, V): Pair<NewK, NewV> $function
     *
     * @return self<NewK, NewV>
     */
    public function map(callable $function): self
    {
        $result = new self(frozen: false);

        foreach ($this->getPairsArray() as $pair) {
            $pair = $function($pair->key, $pair->value);
            $result->set($pair->key, $pair->value);
        }

        return $result->freeze();
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
        return $this->map(static fn (mixed $key, mixed $value) => new Pair($key, $function($value)));
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
     * @return list<Pair<K, V>>
     */
    public function getPairsArray(): array
    {
        $result = [];

        foreach ($this->items as $wrappedKey) {
            $result[] = new Pair($wrappedKey->key, $this->items[$wrappedKey]);
        }

        return $result;
    }

    /**
     * @param iterable<V>    $input
     * @param callable(V): K $valueToKeyFunction
     */
    public static function fromValues(iterable $input, callable $valueToKeyFunction): static
    {
        $result = new static(frozen: false);

        foreach ($input as $value) {
            $key = static::enforceKeyType($valueToKeyFunction($value));
            $value = static::enforceValueType($value);

            $result->set($key, $value);
        }

        return $result->freeze();
    }

    /**
     * @param iterable<mixed>    $input
     * @param int|literal-string $keyKey
     * @param int|literal-string $valueKey
     */
    public static function fromRows(iterable $input, int|string $keyKey, int|string $valueKey): static
    {
        $result = new static(frozen: false);

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

        return $result->freeze();
    }

    /**
     * @param K $key
     *
     * @return $this
     */
    public function removeKey(mixed $key): static
    {
        $this->freezer->protect();

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
        usort($pairs, static fn (Pair $pair1, Pair $pair2): int => $times * $comparator($pair1->value, $pair2->value));

        $result = new static(frozen: false);

        foreach ($pairs as $pair) {
            $result->set($pair->key, $pair->value);
        }

        return $result->freeze();
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
