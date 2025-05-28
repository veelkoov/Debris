<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKey;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Base\Internal\Pair;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Exception\MissingKeyException;
use Veelkoov\Debris\Exception\NoSingleElementException;

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

    //
    // ===== CONSTRUCTOR =======================================
    //

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

    //
    // ===== OF ================================================
    //

    // Not implemented

    //
    // ===== MAGIC METHODS =====================================
    //

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

    //
    // ===== FREEZE ============================================
    //

    /**
     * @return $this
     */
    public function freeze(): static
    {
        $this->freezer->freeze();

        return $this;
    }

    //
    // ===== EMPTY AND COUNT ===================================
    //

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

    //
    // ===== ADD ===============================================
    //

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

    //
    // ===== PLUS ==============================================
    //

    /**
     * @param K $key
     * @param V $value
     */
    public function plus(mixed $key, mixed $value): static
    {
        return (new static($this))->set($key, $value);
    }

    /**
     * @param iterable<K, V> $items
     *
     * @return $this
     */
    public function plusAll(iterable $items): static
    {
        return (new static($this))->setAll($items);
    }

    //
    // ===== REMOVE ============================================
    //

    /**
     * @param V ...$value
     *
     * @return $this
     */
    public function removeValue(mixed ...$value): static
    {
        return $this->removeAllValues($value);
    }

    /**
     * @param iterable<V> $values
     *
     * @return $this
     */
    public function removeAllValues(iterable $values): static
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
    public function removeKey(mixed ...$key): static
    {
        return $this->removeAllKeys($key);
    }

    /**
     * @param iterable<K> $keys
     *
     * @return $this
     */
    public function removeAllKeys(iterable $keys): static
    {
        $this->freezer->protect();

        foreach ($keys as $key) {
            $this->items->detach($this->mappedKeys->get($key));
        }

        return $this;
    }

    //
    // ===== MINUS =============================================
    //

    /**
     * @param V ...$value
     */
    public function minusValue(mixed ...$value): static
    {
        return $this->minusAllValues($value);
    }

    /**
     * @param iterable<V> $values
     */
    public function minusAllValues(iterable $values): static
    {
        return (new static($this))->removeAllValues($values);
    }

    /**
     * @param K ...$key
     */
    public function minusKey(mixed ...$key): static
    {
        return $this->minusAllKeys($key);
    }

    /**
     * @param iterable<K> $keys
     */
    public function minusAllKeys(iterable $keys): static
    {
        return (new static($this))->removeAllKeys($keys);
    }

    //
    // ===== CONTAINS ==========================================
    //

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

    //
    // ===== SORTED ============================================
    //

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

    //
    // ===== JSON SERIALIZE ====================================
    //

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return $this->getPairsArray();
    }

    //
    // ===== ITERATION =========================================
    //

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

    //
    // ===== INTERSECT =========================================
    //

    // Not implemented

    //
    // ===== MAX ===============================================
    //

    // Not implemented

    //
    // ===== ACCESSORS =========================================
    //

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

    //
    // ===== FILTER ============================================
    //

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

    //
    // ===== SINGLE ============================================
    //

    /**
     * @return Pair<K, V>
     */
    public function single(): Pair
    {
        $key = $this->singleKey();

        return new Pair($key, $this->get($key));
    }

    /**
     * @return V
     */
    public function singleValue(): mixed
    {
        $key = $this->singleKey();

        return $this->get($key);
    }

    /**
     * @return K
     */
    public function singleKey(): mixed
    {
        if (1 !== $this->count()) {
            throw new NoSingleElementException('The map has '.$this->count().' items instead of exactly one.');
        }

        $this->items->rewind();

        return $this->items->current()->key;
    }

    //
    // ===== RANDOM ============================================
    //

    /**
     * @return Pair<K, V>
     */
    public function random(): Pair
    {
        $key = $this->randomKey();
        $value = $this->get($key);

        return new Pair($key, $value);
    }

    /**
     * @return V
     */
    public function randomValue(): mixed
    {
        return $this->get($this->randomKey());
    }

    /**
     * @return K
     */
    public function randomKey(): mixed
    {
        if ($this->isEmpty()) {
            throw new EmptyCollectionException('The map is empty.');
        }

        $keys = $this->getKeysArray();

        return $keys[array_rand($keys)];
    }

    //
    // ===== MAP ===============================================
    //

    /**
     * @template OutV of object|scalar|null
     * @template OutK of object|scalar|null
     *
     * @param (callable(K, V): array{OutK, OutV})|(\Closure(K, V): array{OutK, OutV}) $mapFunction
     *
     * @return self<OutK, OutV>
     */
    public function map(callable|\Closure $mapFunction): self
    {
        return new self((function () use ($mapFunction) {
            foreach ($this as $key => $value) {
                $pair = $mapFunction($key, $value);

                yield $pair[0] => $pair[1];
            }
        })());
    }

    /**
     * @template OutK of object|scalar|null
     *
     * @param (callable(K): OutK)|(\Closure(K): OutK) $mapFunction
     *
     * @return self<OutK, V>
     */
    public function mapKeys(callable|\Closure $mapFunction): self
    {
        return $this->map(static fn (mixed $key, mixed $value) => [$mapFunction($key), $value]);
    }

    /**
     * @template OutV of object|scalar|null
     *
     * @param (callable(V): OutV)|(\Closure(V): OutV) $mapFunction
     *
     * @return self<K, OutV>
     */
    public function mapValues(callable|\Closure $mapFunction): self
    {
        return $this->map(static fn (mixed $key, mixed $value) => [$key, $mapFunction($value)]);
    }

    //
    // ===== MAP FROM ==========================================
    //

    /**
     * @template InV
     * @template InK
     * @template OutK of object|scalar|null
     * @template OutV of object|scalar|null
     *
     * @param iterable<InK, InV>                                                              $source
     * @param (callable(InV, InK): array{OutK, OutV})|(\Closure(InV, InK): array{OutK, OutV}) $mapFunction
     *
     * @return static<OutK, OutV>
     */
    public static function mapFrom(iterable $source, callable|\Closure $mapFunction): self
    {
        return new static((static function () use ($source, $mapFunction) {
            foreach ($source as $key => $value) {
                $pair = $mapFunction($value, $key);

                yield $pair[0] => $pair[1];
            }
        })());
    }

    //
    // ===== GET ARRAY =========================================
    //

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

    //
    // ===== ANY ===============================================
    //

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
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function anyValue(callable|\Closure $testFunction): bool
    {
        return $this->any(static fn (mixed $key, mixed $value) => $testFunction($value));
    }

    /**
     * @param (callable(K): bool)|(\Closure(K): bool) $testFunction
     */
    public function anyKey(callable|\Closure $testFunction): bool
    {
        return $this->any(static fn (mixed $key, mixed $value) => $testFunction($key));
    }

    //
    // ===== ALL ===============================================
    //

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
    public function allValues(callable|\Closure $testFunction): bool
    {
        return $this->all(static fn (mixed $key, mixed $value) => $testFunction($value));
    }

    /**
     * @param (callable(K): bool)|(\Closure(K): bool) $testFunction
     */
    public function allKeys(callable|\Closure $testFunction): bool
    {
        return $this->all(static fn (mixed $key, mixed $value) => $testFunction($key));
    }

    //
    // ===== SHUFFLE ===========================================
    //

    public function shuffle(): static
    {
        $keys = $this->getKeys()->getValuesArray();
        shuffle($keys);

        return new static((function () use ($keys) {
            foreach ($keys as $key) {
                yield $key => $this->get($key);
            }
        })());
    }

    //
    // ===== SLICE =============================================
    //

    public function slice(int $offset, ?int $length = null): static
    {
        $keys = \array_slice($this->getKeys()->getValuesArray(), $offset, $length);

        return new static((function () use ($keys) {
            foreach ($keys as $key) {
                yield $key => $this->get($key);
            }
        })());
    }

    //
    // ===== UNIQUE ============================================
    //

    // Not implemented

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
