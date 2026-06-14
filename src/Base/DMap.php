<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKey;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Exception\MissingKeyException;
use Veelkoov\Debris\Exception\NoSingleElementException;
use Veelkoov\Debris\Maps\Pair;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 *
 * @implements Map<K, V>
 */
class DMap implements Map
{
    protected readonly Freezer $freezer;

    /**
     * @var \SplObjectStorage<MapKey<K>, V>
     */
    protected \SplObjectStorage $items;

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
    // ===== OF ================================================
    //

    // Not implemented

    //
    // ===== FROM UNSAFE =======================================
    //

    /**
     * To be used on unverified sources. Returns instance guaranteed to have values of the desired type.
     *
     * @throws \InvalidArgumentException
     */
    public static function fromUnsafe(mixed $iterable): static
    {
        if (!is_iterable($iterable)) {
            throw new \InvalidArgumentException('Expected an iterable.');
        }

        return new static((static function () use ($iterable) {
            foreach ($iterable as $key => $value) {
                if (!static::isValidKey($key)) {
                    throw new \InvalidArgumentException('Illegal key type.');
                }

                if (!static::isValidValue($value)) {
                    throw new \InvalidArgumentException('Illegal value type.');
                }

                yield $key => $value;
            }
        })());
    }

    //
    // ===== FREEZE ============================================
    //

    public function freeze(): static
    {
        $this->freezer->freeze();

        return $this;
    }

    //
    // ===== EMPTY AND COUNT ===================================
    //

    #[\Override]
    public function isEmpty(): bool
    {
        return 0 === $this->items->count();
    }

    #[\Override]
    public function isNotEmpty(): bool
    {
        return 0 !== $this->items->count();
    }

    #[\Override]
    public function count(): int
    {
        return $this->items->count();
    }

    //
    // ===== ADD ===============================================
    //

    public function set(mixed $key, mixed $value): static
    {
        $this->freezer->protect();

        $this->items[$this->mappedKeys->get($key)] = $value;

        return $this;
    }

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

    public function plus(mixed $key, mixed $value): static
    {
        return (new static($this))->set($key, $value);
    }

    public function plusAll(iterable $items): static
    {
        return (new static($this))->setAll($items);
    }

    //
    // ===== REMOVE ============================================
    //

    public function removeValue(mixed ...$value): static
    {
        return $this->removeAllValues($value);
    }

    public function removeAllValues(iterable $values): static
    {
        $this->freezer->protect();

        foreach ($values as $removedValue) {
            foreach ($this->items as $wrappedKey) {
                if ($this->items[$wrappedKey] === $removedValue) {
                    $this->items->offsetUnset($wrappedKey);

                    break;
                }
            }
        }

        return $this;
    }

    public function removeKey(mixed ...$key): static
    {
        return $this->removeAllKeys($key);
    }

    public function removeAllKeys(iterable $keys): static
    {
        $this->freezer->protect();

        foreach ($keys as $key) {
            $this->items->offsetUnset($this->mappedKeys->get($key));
        }

        return $this;
    }

    //
    // ===== MINUS =============================================
    //

    public function minusValue(mixed ...$value): static
    {
        return $this->minusAllValues($value);
    }

    public function minusAllValues(iterable $values): static
    {
        return (new static($this))->removeAllValues($values);
    }

    public function minusKey(mixed ...$key): static
    {
        return $this->minusAllKeys($key);
    }

    public function minusAllKeys(iterable $keys): static
    {
        return (new static($this))->removeAllKeys($keys);
    }

    //
    // ===== CONTAINS ==========================================
    //

    public function contains(mixed $value): bool
    {
        foreach ($this->items as $key) {
            if ($this->items[$key] === $value) {
                return true;
            }
        }

        return false;
    }

    public function hasKey(mixed $key): bool
    {
        return $this->items->offsetExists($this->mappedKeys->get($key));
    }

    //
    // ===== SORTED ============================================
    //

    public function sorted(callable|\Closure|null $comparator = null, bool $reverse = false): static
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

    public function getOrSet(mixed $key, callable|\Closure $newValueFunction): mixed
    {
        if (!$this->hasKey($key)) {
            $this->set($key, $newValueFunction());
        }

        return $this->get($key);
    }

    public function getOrSetAs(mixed $key, mixed $newValue): mixed
    {
        if (!$this->hasKey($key)) {
            $this->set($key, $newValue);
        }

        return $this->get($key);
    }

    public function getOrDefault(mixed $key, callable|\Closure $defaultValueFunction): mixed
    {
        if (!$this->hasKey($key)) {
            return $defaultValueFunction();
        }

        return $this->get($key);
    }

    public function getOrDefaultOf(mixed $key, mixed $defaultValue): mixed
    {
        return $this->hasKey($key) ? $this->get($key) : $defaultValue;
    }

    //
    // ===== FILTER ============================================
    //

    public function filter(callable|\Closure $filter): static
    {
        $result = new static();

        foreach ($this->getKeysArray() as $key) {
            $value = $this->get($key);

            if ($filter($key, $value)) {
                $result->set($key, $value);
            }
        }

        return $result;
    }

    public function filterNot(callable|\Closure $filter): static
    {
        return $this->filter(static fn (mixed $key, mixed $value) => !$filter($key, $value));
    }

    public function filterValues(callable|\Closure $filter): static
    {
        return $this->filter(static fn (mixed $key, mixed $value) => $filter($value));
    }

    public function filterValuesNot(callable|\Closure $filter): static
    {
        return $this->filter(static fn (mixed $key, mixed $value) => !$filter($value));
    }

    public function filterKeys(callable|\Closure $filter): static
    {
        return $this->filter(static fn (mixed $key, mixed $value) => $filter($key));
    }

    public function filterKeysNot(callable|\Closure $filter): static
    {
        return $this->filter(static fn (mixed $key, mixed $value) => !$filter($key));
    }

    //
    // ===== SINGLE ============================================
    //

    public function single(): Pair
    {
        $key = $this->singleKey();

        return new Pair($key, $this->get($key));
    }

    public function singleValue(): mixed
    {
        $key = $this->singleKey();

        return $this->get($key);
    }

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

    public function random(): Pair
    {
        $key = $this->randomKey();
        $value = $this->get($key);

        return new Pair($key, $value);
    }

    public function randomValue(): mixed
    {
        return $this->get($this->randomKey());
    }

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

    public function map(callable|\Closure $function): static
    {
        return new static((function () use ($function) {
            foreach ($this as $key => $value) {
                $pair = $function($key, $value);

                yield $pair[0] => $pair[1];
            }
        })());
    }

    public function mapInto(callable|\Closure $function, Map $target): Map
    {
        foreach ($this as $key => $value) {
            $pair = $function($key, $value);

            $target->set($pair[0], $pair[1]);
        }

        return $target;
    }

    public function mapKeys(callable|\Closure $function): Map
    {
        return $this->map(static fn (mixed $key, mixed $value) => [$function($key), $value]);
    }

    public function mapKeysInto(callable|\Closure $function, Map $target): Map
    {
        return $this->mapInto(static fn (mixed $key, mixed $value) => [$function($key), $value], $target);
    }

    public function mapValues(callable|\Closure $function): Map
    {
        return $this->map(static fn (mixed $key, mixed $value) => [$key, $function($value)]);
    }

    public function mapValuesInto(callable|\Closure $function, Map $target): Map
    {
        return $this->mapInto(static fn (mixed $key, mixed $value) => [$key, $function($value)], $target);
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

    public function getKeysArray(): array
    {
        $result = [];

        foreach ($this->items as $key) {
            $result[] = $key->key;
        }

        return $result;
    }

    public function getValuesArray(): array
    {
        $result = [];

        foreach ($this->items as $key) {
            $result[] = $this->items[$key];
        }

        return $result;
    }

    public function getKeys(): DSet
    {
        return new DSet($this->getKeysArray());
    }

    public function getValues(): DList
    {
        return new DList($this->getValuesArray());
    }

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

    public function any(callable|\Closure $testFunction): bool
    {
        foreach ($this as $key => $value) {
            if ($testFunction($key, $value)) {
                return true;
            }
        }

        return false;
    }

    public function anyValue(callable|\Closure $testFunction): bool
    {
        return $this->any(static fn (mixed $key, mixed $value) => $testFunction($value));
    }

    public function anyKey(callable|\Closure $testFunction): bool
    {
        return $this->any(static fn (mixed $key, mixed $value) => $testFunction($key));
    }

    //
    // ===== ALL ===============================================
    //

    public function all(callable|\Closure $testFunction): bool
    {
        foreach ($this as $key => $value) {
            if (!$testFunction($key, $value)) {
                return false;
            }
        }

        return true;
    }

    public function allValues(callable|\Closure $testFunction): bool
    {
        return $this->all(static fn (mixed $key, mixed $value) => $testFunction($value));
    }

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

    //
    // ===== OTHER ============================================
    //

    public function flip(): static
    {
        $result = new static();

        foreach ($this as $key => $value) {
            $result->set($value, $key);
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
    public static function fromValues(iterable $input, callable|\Closure $valueToKeyFunction): static
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

    //
    // ===== VALIDATION ========================================
    //

    /**
     * @param array<mixed>|\ArrayAccess<mixed, mixed> $input
     */
    protected static function enforceIsArrayAndKeyExistsGetKey(array|\ArrayAccess $input, int|string $key): mixed
    {
        return $input[$key];
    }

    /**
     * @param K $key
     *
     * @return K
     */
    protected static function enforceKeyType(mixed $key): mixed
    {
        throw new \LogicException('Not implemented. '.__METHOD__.' needs to be overridden.');
    }

    /**
     * @phpstan-assert-if-true K $key
     */
    protected static function isValidKey(mixed $key): bool
    {
        throw new \LogicException('Not implemented. '.__METHOD__.' needs to be overridden.');
    }

    /**
     * @param V $value
     *
     * @return V
     */
    protected static function enforceValueType(mixed $value): mixed
    {
        throw new \LogicException('Not implemented. '.__METHOD__.' needs to be overridden.');
    }

    /**
     * @phpstan-assert-if-true V $value
     */
    protected static function isValidValue(mixed $value): bool
    {
        throw new \LogicException('Not implemented. '.__METHOD__.' needs to be overridden.');
    }
}
