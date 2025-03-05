<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKey;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Base\Internal\Pair;
use Veelkoov\Debris\Exception\MissingKeyException;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 *
 * @implements \Iterator<K, V>
 */
class DMap implements \Iterator, \JsonSerializable
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
     * @param iterable<K, V> $items
     */
    final public function __construct(iterable $items = [], bool $frozen = false)
    {
        $this->mappedKeys = new MapKeyMapper();
        $this->items = new \SplObjectStorage();
        $this->freezer = new Freezer($this, false);

        $this->setAll($items);

        if ($frozen) {
            $this->freezer->freeze();
        }
    }

    /**
     * @param K $key
     *
     * @return V
     */
    public function __get(mixed $key): mixed
    {
        return $this->get($key);
    }

    /**
     * @param K $key
     */
    public function __isset(mixed $key): bool
    {
        return $this->hasKey($key);
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
     * @param iterable<K, V> $items
     *
     * @return $this
     */
    public function setAll(iterable $items): static
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * @param K $key
     * @param V $value
     */
    public function plus(mixed $key, mixed $value): static
    {
        return (new static($this))
            ->set($key, $value)
        ;
    }

    /**
     * @param iterable<K, V> $items
     *
     * @return $this
     */
    public function plusAll(iterable $items): static
    {
        return (new static($this))
            ->setAll($items)
        ;
    }

    /**
     * @param V ...$value
     *
     * @return $this
     */
    public function remove(mixed ...$value): static
    {
        return $this->removeAll($value);
    }

    /**
     * @param iterable<V> $values
     *
     * @return $this
     */
    public function removeAll(iterable $values): static
    {
        $this->freezer->protect();

        foreach ($values as $removedValue) {
            foreach ($this->items as $wrappedKey) {
                if ($this->items[$wrappedKey] === $removedValue) {
                    $this->items->detach($wrappedKey);

                    break;
                }
            }
        }

        return $this;
    }

    /**
     * @param K ...$key
     *
     * @return $this
     */
    public function unset(mixed ...$key): static
    {
        return $this->unsetAll($key);
    }

    /**
     * @param iterable<K> $keys
     *
     * @return $this
     */
    public function unsetAll(iterable $keys): static
    {
        $this->freezer->protect();

        foreach ($keys as $key) {
            $this->items->detach($this->mappedKeys->get($key));
        }

        return $this;
    }

    /**
     * @param V ...$value
     */
    public function minus(mixed ...$value): static
    {
        return $this->minusAll($value);
    }

    /**
     * @param iterable<V> $values
     */
    public function minusAll(iterable $values): static
    {
        return (new static($this))
            ->removeAll($values)
        ;
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
     * @param null|(callable(Pair<K, V>, Pair<K, V>): int)|(\Closure(Pair<K, V>, Pair<K, V>): int) $comparator
     */
    public function sorted(null|callable|\Closure $comparator = null, bool $reverse = false): static
    {
        $times = $reverse ? -1 : 1;
        $comparator ??= static fn (Pair $a, Pair $b): int => $a->value <=> $b->value;

        $pairs = $this->getPairsArray();
        usort($pairs, static fn (Pair $pair1, Pair $pair2): int => $times * $comparator($pair1, $pair2));

        $result = new static();

        foreach ($pairs as $pair) {
            $result->set($pair->key, $pair->value);
        }

        return $result;
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return $this->getPairsArray();
    }

    /**
     * @param K $key
     *
     * @return V
     */
    public function get(mixed $key): mixed
    {
        try {
            return $this->items[$this->mappedKeys->get($key)];
        } catch (\UnexpectedValueException $exception) {
            $message = 'Missing '.get_debug_type($key).' key';

            if (!\is_object($key)) {
                $message .= ': '.var_export($key, return: true);
            }

            throw new MissingKeyException($message, previous: $exception);
        }
    }

    /**
     * @param K                               $key
     * @param (callable(): V)|(\Closure(): V) $newValueFunction
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
     * @param K                                       $key
     * @param (callable(): (T|V))|(\Closure(): (T|V)) $defaultValueFunction
     *
     * @return T|V
     */
    public function getOrDefault(mixed $key, callable|\Closure $defaultValueFunction): mixed
    {
        if (!$this->hasKey($key)) {
            return $defaultValueFunction();
        }

        return $this->get($key);
    }

    /**
     * @param (callable(K, V): bool)|(\Closure(K, V): bool) $function
     */
    public function filter(callable|\Closure $function): static
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
     * @param (callable(V): bool)|(\Closure(V): bool) $function
     */
    public function filterValues(callable|\Closure $function): static
    {
        return $this->filter(static fn (mixed $key, mixed $value) => $function($value));
    }

    /**
     * @param (callable(K): bool)|(\Closure(K): bool) $function
     */
    public function filterKeys(callable|\Closure $function): static
    {
        return $this->filter(static fn (mixed $key, mixed $value) => $function($key));
    }

    /**
     * @template OutV of object|scalar|null
     * @template OutK of object|scalar|null
     *
     * @param (callable(K, V): Pair<OutK, OutV>)|(\Closure(K, V): Pair<OutK, OutV>) $function
     *
     * @return self<OutK, OutV>
     */
    public function map(callable|\Closure $function): self
    {
        $result = new self();

        foreach ($this->getPairsArray() as $pair) {
            $pair = $function($pair->key, $pair->value);
            $result->set($pair->key, $pair->value);
        }

        return $result;
    }

    /**
     * @template OutV of object|scalar|null
     *
     * @param (callable(V): OutV)|(\Closure(V): OutV) $function
     *
     * @return self<K, OutV>
     */
    public function mapValues(callable|\Closure $function): self
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
     * @template OutV of object|scalar|null
     * @template OutK of object|scalar|null
     *
     * @param iterable<OutV>                                $input
     * @param (callable(OutV): OutK)|(\Closure(OutV): OutK) $valueToKeyFunction
     *
     * @return static<OutK, OutV>
     */
    public static function fromValues(iterable $input, callable|\Closure $valueToKeyFunction): self
    {
        $result = new static();

        foreach ($input as $value) {
            $key = $valueToKeyFunction($value);

            /** @phpstan-ignore argument.type (Part of validation) */
            $key = static::enforceKeyType($key);

            /** @phpstan-ignore argument.type (Part of validation) */
            $value = static::enforceValueType($value);

            $result->set($key, $value);
        }

        return $result;
    }

    /**
     * @template OutV of object|scalar|null
     * @template OutK of object|scalar|null
     *
     * @param iterable<OutK>                                $input
     * @param (callable(OutK): OutV)|(\Closure(OutK): OutV) $keyToValueFunction
     *
     * @return static<OutK, OutV>
     */
    public static function fromKeys(iterable $input, callable|\Closure $keyToValueFunction): self
    {
        $result = new static();

        foreach ($input as $key) {
            $value = $keyToValueFunction($key);

            /** @phpstan-ignore argument.type (Part of validation) */
            $key = static::enforceKeyType($key);

            /** @phpstan-ignore argument.type (Part of validation) */
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
     * @return self<V, K>
     */
    public function flip(): self
    {
        $result = new self();

        foreach ($this as $key => $value) {
            $result->set($value, $key);
        }

        return $result;
    }

    #[\Override]
    public function current(): mixed
    {
        return $this->items[$this->items->current()];
    }

    #[\Override]
    public function next(): void
    {
        $this->items->next();
    }

    #[\Override]
    public function key(): mixed
    {
        return $this->items->current()->key;
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->items->valid();
    }

    #[\Override]
    public function rewind(): void
    {
        $this->items->rewind();
    }

    /**
     * @param (callable(K, V): bool)|(\Closure(K, V): bool) $testFunction
     */
    public function any(callable|\Closure $testFunction): bool
    {
        foreach ($this as $key => $value) {
            if ($testFunction($key, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param (callable(K, V): bool)|(\Closure(K, V): bool) $testFunction
     */
    public function all(callable|\Closure $testFunction): bool
    {
        foreach ($this as $key => $value) {
            if (!$testFunction($key, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function anyValue(callable|\Closure $testFunction): bool
    {
        return $this->any(static fn (mixed $key, mixed $value) => $testFunction($value));
    }

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function allValues(callable|\Closure $testFunction): bool
    {
        return $this->all(static fn (mixed $key, mixed $value) => $testFunction($value));
    }

    /**
     * @param (callable(K): bool)|(\Closure(K): bool) $testFunction
     */
    public function anyKey(callable|\Closure $testFunction): bool
    {
        return $this->any(static fn (mixed $key, mixed $value) => $testFunction($key));
    }

    /**
     * @param (callable(K): bool)|(\Closure(K): bool) $testFunction
     */
    public function allKeys(callable|\Closure $testFunction): bool
    {
        return $this->all(static fn (mixed $key, mixed $value) => $testFunction($key));
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
