<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\DMap as Out;
use Veelkoov\Debris\Maps\Pair;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 *
 * @extends Collection<K, V>
 * @extends \Iterator<K, V>
 */
interface Map extends Collection, \Iterator
{
    /**
     * @return $this
     */
    public function freeze(): static;

    /**
     * @phpstan-assert-if-false non-empty-list<K> $this->getKeysArray()
     */
    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function count(): int;

    /**
     * @param K $key
     * @param V $value
     *
     * @return $this
     */
    public function set(mixed $key, mixed $value): static;

    /**
     * @param iterable<K, V> $items
     *
     * @return $this
     */
    public function setAll(iterable $items): static;

    /**
     * @param K $key
     * @param V $value
     */
    public function plus(mixed $key, mixed $value): static;

    /**
     * @param iterable<K, V> $items
     */
    public function plusAll(iterable $items): static;

    /**
     * @param V ...$value
     *
     * @return $this
     */
    public function removeValue(mixed ...$value): static;

    /**
     * @param iterable<V> $values
     *
     * @return $this
     */
    public function removeAllValues(iterable $values): static;

    /**
     * @param K ...$key
     *
     * @return $this
     */
    public function removeKey(mixed ...$key): static;

    /**
     * @param iterable<K> $keys
     *
     * @return $this
     */
    public function removeAllKeys(iterable $keys): static;

    /**
     * @param V ...$value
     */
    public function minusValue(mixed ...$value): static;

    /**
     * @param iterable<V> $values
     */
    public function minusAllValues(iterable $values): static;

    /**
     * @param K ...$key
     */
    public function minusKey(mixed ...$key): static;

    /**
     * @param iterable<K> $keys
     */
    public function minusAllKeys(iterable $keys): static;

    /**
     * @param V $value
     */
    public function contains(mixed $value): bool;

    /**
     * @param K $key
     */
    public function hasKey(mixed $key): bool;

    /**
     * @param null|(callable(Pair<K, V>, Pair<K, V>): int)|(\Closure(Pair<K, V>, Pair<K, V>): int) $comparator
     */
    public function sorted(callable|\Closure|null $comparator = null, bool $reverse = false): static;

    public function jsonSerialize(): mixed;

    public function current(): mixed;

    public function next(): void;

    public function key(): mixed;

    public function valid(): bool;

    public function rewind(): void;

    /**
     * @param K $key
     *
     * @return V
     */
    public function get(mixed $key): mixed;

    /**
     * @param K                               $key
     * @param (callable(): V)|(\Closure(): V) $newValueFunction
     *
     * @return V
     */
    public function getOrSet(mixed $key, callable|\Closure $newValueFunction): mixed;

    /**
     * @param K $key
     * @param V $newValue
     *
     * @return V
     */
    public function getOrSetAs(mixed $key, mixed $newValue): mixed;

    /**
     * @template T
     *
     * @param K                                       $key
     * @param (callable(): (T|V))|(\Closure(): (T|V)) $defaultValueFunction
     *
     * @return T|V
     */
    public function getOrDefault(mixed $key, callable|\Closure $defaultValueFunction): mixed;

    /**
     * @template T
     *
     * @param K   $key
     * @param T|V $defaultValue
     *
     * @return T|V
     */
    public function getOrDefaultOf(mixed $key, mixed $defaultValue): mixed;

    /**
     * @param (callable(K, V): bool)|(\Closure(K, V): bool) $filter
     */
    public function filter(callable|\Closure $filter): static;

    /**
     * @param (callable(K, V): bool)|(\Closure(K, V): bool) $filter
     */
    public function filterNot(callable|\Closure $filter): static;

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $filter
     */
    public function filterValues(callable|\Closure $filter): static;

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $filter
     */
    public function filterValuesNot(callable|\Closure $filter): static;

    /**
     * @param (callable(K): bool)|(\Closure(K): bool) $filter
     */
    public function filterKeys(callable|\Closure $filter): static;

    /**
     * @param (callable(K): bool)|(\Closure(K): bool) $filter
     */
    public function filterKeysNot(callable|\Closure $filter): static;

    /**
     * @return Pair<K, V>
     */
    public function single(): Pair;

    /**
     * @return V
     */
    public function singleValue(): mixed;

    /**
     * @return K
     */
    public function singleKey(): mixed;

    /**
     * @return Pair<K, V>
     */
    public function random(): Pair;

    /**
     * @return V
     */
    public function randomValue(): mixed;

    /**
     * @return K
     */
    public function randomKey(): mixed;

    /**
     * @template OutV of object|scalar|null
     * @template OutK of object|scalar|null
     *
     * @param (callable(K, V): array{OutK, OutV})|(\Closure(K, V): array{OutK, OutV}) $function
     *
     * @return static<OutK, OutV>
     */
    public function map(callable|\Closure $function): static;

    /**
     * @template OutV of object|scalar|null
     * @template OutK of object|scalar|null
     * @template Out of Map<OutK, OutV>
     *
     * @param (callable(K, V): array{OutK, OutV})|(\Closure(K, V): array{OutK, OutV}) $function
     * @param Out                                                                     $target
     *
     * @return Out
     */
    public function mapInto(callable|\Closure $function, self $target): self;

    /**
     * @template OutK of object|scalar|null
     *
     * @param (callable(K): OutK)|(\Closure(K): OutK) $function
     *
     * @return Map<OutK, V>
     */
    public function mapKeys(callable|\Closure $function): self;

    /**
     * @template OutK of object|scalar|null
     * @template Out of Map<OutK, V>
     *
     * @param (callable(K): OutK)|(\Closure(K): OutK) $function
     * @param Out                                     $target
     *
     * @return Out
     */
    public function mapKeysInto(callable|\Closure $function, self $target): self;

    /**
     * @template OutV of object|scalar|null
     *
     * @param (callable(V): OutV)|(\Closure(V): OutV) $function
     *
     * @return Map<K, OutV>
     */
    public function mapValues(callable|\Closure $function): self;

    /**
     * @template OutV of object|scalar|null
     * @template Out of Map<K, OutV>
     *
     * @param (callable(V): OutV)|(\Closure(V): OutV) $function
     * @param Out                                     $target
     *
     * @return Out
     */
    public function mapValuesInto(callable|\Closure $function, self $target): self;

    /**
     * @return list<K>
     */
    public function getKeysArray(): array;

    /**
     * @return list<V>
     */
    public function getValuesArray(): array;

    /**
     * @return DSet<K>
     */
    public function getKeys(): DSet;

    /**
     * @return DList<V>
     */
    public function getValues(): DList;

    /**
     * @return list<Pair<K, V>>
     */
    public function getPairsArray(): array;

    /**
     * @param (callable(K, V): bool)|(\Closure(K, V): bool) $testFunction
     */
    public function any(callable|\Closure $testFunction): bool;

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function anyValue(callable|\Closure $testFunction): bool;

    /**
     * @param (callable(K): bool)|(\Closure(K): bool) $testFunction
     */
    public function anyKey(callable|\Closure $testFunction): bool;

    /**
     * @param (callable(K, V): bool)|(\Closure(K, V): bool) $testFunction
     */
    public function all(callable|\Closure $testFunction): bool;

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function allValues(callable|\Closure $testFunction): bool;

    /**
     * @param (callable(K): bool)|(\Closure(K): bool) $testFunction
     */
    public function allKeys(callable|\Closure $testFunction): bool;

    public function shuffle(): static;

    public function slice(int $offset, ?int $length = null): static;

    /**
     * @return static<V, K>
     */
    public function flip(): static;
}
