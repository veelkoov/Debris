<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Exception\MissingKeyException;
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
    //
    // ===== OF ================================================
    //

    //
    // ===== FROM UNSAFE =======================================
    //

    /**
     * To be used on unverified sources. Returns instance guaranteed to have values of the desired type.
     *
     * @throws \InvalidArgumentException
     */
    public static function fromUnsafe(mixed $iterable): static;

    //
    // ===== FREEZE ============================================
    //

    /**
     * @return $this
     */
    public function freeze(): static;

    //
    // ===== EMPTY AND COUNT ===================================
    //

    /**
     * @phpstan-assert-if-false non-empty-list<K> $this->getKeysArray()
     */
    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function count(): int;

    //
    // ===== ADD ===============================================
    //

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

    //
    // ===== PLUS ==============================================
    //

    /**
     * @param K $key
     * @param V $value
     */
    public function plus(mixed $key, mixed $value): static;

    /**
     * @param iterable<K, V> $items
     */
    public function plusAll(iterable $items): static;

    //
    // ===== REMOVE ============================================
    //

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

    //
    // ===== MINUS =============================================
    //

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

    //
    // ===== CONTAINS ==========================================
    //

    /**
     * @param V $value
     */
    public function contains(mixed $value): bool;

    /**
     * @param K $key
     */
    public function hasKey(mixed $key): bool;

    //
    // ===== SHUFFLE ===========================================
    //

    public function shuffle(): static;

    //
    // ===== SORTED ============================================
    //

    /**
     * @param null|(callable(Pair<K, V>, Pair<K, V>): int)|(\Closure(Pair<K, V>, Pair<K, V>): int) $comparator
     */
    public function sorted(callable|\Closure|null $comparator = null, bool $reverse = false): static;

    //
    // ===== JSON SERIALIZE ====================================
    //

    public function jsonSerialize(): mixed;

    //
    // ===== ITERATION =========================================
    //

    /**
     * @return V
     */
    public function current(): mixed;

    public function next(): void;

    /**
     * @return K
     */
    public function key(): mixed;

    public function valid(): bool;

    public function rewind(): void;

    //
    // ===== INTERSECT =========================================
    //

    //
    // ===== ACCESSORS =========================================
    //

    /**
     * @param K $key
     *
     * @return V
     *
     * @throws MissingKeyException
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

    //
    // ===== MAX ===============================================
    //

    //
    // ===== FILTER ============================================
    //

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

    //
    // ===== SINGLE ============================================
    //

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

    //
    // ===== RANDOM ============================================
    //

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

    //
    // ===== MAP ===============================================
    //

    /**
     * @param (callable(K, V): array{K, V})|(\Closure(K, V): array{K, V}) $function
     *
     * @return static<K, V>
     */
    public function map(callable|\Closure $function): static;

    /**
     * @param (callable(K): K)|(\Closure(K): K) $function
     *
     * @return static<K, V>
     */
    public function mapKeys(callable|\Closure $function): static;

    /**
     * @param (callable(V): V)|(\Closure(V): V) $function
     *
     * @return static<K, V>
     */
    public function mapValues(callable|\Closure $function): static;

    //
    // ===== MAP FROM ==========================================
    //

    /**
     * @template InV
     * @template InK
     * @template OutK of K
     * @template OutV of V
     *
     * @param iterable<InK, InV>                                                              $source
     * @param (callable(InV, InK): array{OutK, OutV})|(\Closure(InV, InK): array{OutK, OutV}) $mapFunction
     *
     * @return static<OutK, OutV>
     */
    public static function mapFrom(iterable $source, callable|\Closure $mapFunction): static;

    //
    // ===== MAP INTO ==========================================
    //

    /**
     * @template OutV of object|scalar|null
     * @template OutK of object|scalar|null
     * @template Out of Map<OutK, OutV>
     *
     * @param Out                                                                     $target
     * @param (callable(K, V): array{OutK, OutV})|(\Closure(K, V): array{OutK, OutV}) $function
     *
     * @return Out
     */
    public function mapInto(self $target, callable|\Closure $function): self;

    /**
     * @template OutK of object|scalar|null
     * @template Out of Map<OutK, V>
     *
     * @param Out                                     $target
     * @param (callable(K): OutK)|(\Closure(K): OutK) $function
     *
     * @return Out
     */
    public function mapKeysInto(self $target, callable|\Closure $function): self;

    /**
     * @template OutV of object|scalar|null
     * @template Out of Map<K, OutV>
     *
     * @param Out                                     $target
     * @param (callable(V): OutV)|(\Closure(V): OutV) $function
     *
     * @return Out
     */
    public function mapValuesInto(self $target, callable|\Closure $function): self;

    //
    // ===== GET ARRAY =========================================
    //

    /**
     * @return list<K>
     */
    public function getKeysArray(): array;

    /**
     * @return list<V>
     */
    public function getValuesArray(): array;

    /**
     * @return Set<K>
     */
    public function getKeys(): Set;

    /**
     * @return Vec<V>
     */
    public function getValues(): Vec;

    /**
     * @return list<Pair<K, V>>
     */
    public function getPairsArray(): array;

    //
    // ===== ANY ===============================================
    //

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

    //
    // ===== ALL ===============================================
    //

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

    //
    // ===== SLICE =============================================
    //

    public function slice(int $offset, ?int $length = null): static;

    //
    // ===== UNIQUE ============================================
    //

    //
    // ===== OTHER ============================================
    //

    /**
     * @template OutV of object|scalar|null
     * @template OutK of object|scalar|null
     *
     * @param iterable<OutV>                                $input
     * @param (callable(OutV): OutK)|(\Closure(OutV): OutK) $valueToKeyFunction
     *
     * @return static<OutK, OutV>
     */
    public static function fromValues(iterable $input, callable|\Closure $valueToKeyFunction): static;

    /**
     * @template OutV of object|scalar|null
     * @template OutK of object|scalar|null
     *
     * @param iterable<OutK>                                $input
     * @param (callable(OutK): OutV)|(\Closure(OutK): OutV) $keyToValueFunction
     *
     * @return static<OutK, OutV>
     */
    public static function fromKeys(iterable $input, callable|\Closure $keyToValueFunction): static;

    /**
     * @param iterable<mixed>    $input
     * @param int|literal-string $keyKey
     * @param int|literal-string $valueKey
     */
    public static function fromRows(iterable $input, int|string $keyKey, int|string $valueKey): static;

    /**
     * @return Map<V, K>
     */
    public function flip(): self;
}
