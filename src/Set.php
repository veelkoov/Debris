<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

/**
 * @template V of object|scalar|null
 *
 * @extends Collection<int, V>
 * @extends \IteratorAggregate<int, V>
 */
interface Set extends Collection, \IteratorAggregate
{
    //
    // ===== OF ================================================
    //

    /**
     * @param V ...$items
     */
    public static function of(mixed ...$items): static;

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

    #[\Override]
    public function isEmpty(): bool;

    #[\Override]
    public function isNotEmpty(): bool;

    #[\Override]
    public function count(): int;

    //
    // ===== ADD ===============================================
    //

    /**
     * @param V ...$value
     *
     * @return $this
     */
    public function add(mixed ...$value): static;

    /**
     * @param iterable<V> $values
     *
     * @return $this
     */
    public function addAll(iterable $values): static;

    //
    // ===== PLUS ==============================================
    //

    /**
     * @param V ...$value
     */
    public function plus(mixed ...$value): static;

    /**
     * @param iterable<V> $values
     */
    public function plusAll(iterable $values): static;

    //
    // ===== REMOVE ============================================
    //

    /**
     * @param V ...$value
     *
     * @return $this
     */
    public function remove(mixed ...$value): static;

    /**
     * @param iterable<V> $values
     *
     * @return $this
     */
    public function removeAll(iterable $values): static;

    //
    // ===== MINUS =============================================
    //

    /**
     * @param V ...$value
     */
    public function minus(mixed ...$value): static;

    /**
     * @param iterable<V> $values
     */
    public function minusAll(iterable $values): static;

    //
    // ===== CONTAINS ==========================================
    //

    /**
     * @param V $value
     */
    public function contains(mixed $value): bool;

    //
    // ===== SHUFFLE ===========================================
    //

    public function shuffle(): static;

    //
    // ===== SORTED ============================================
    //

    /**
     * @param null|(callable(V, V): int)|(\Closure(V, V): int) $comparator
     */
    public function sorted(callable|\Closure|null $comparator = null, bool $reverse = false): static;

    //
    // ===== JSON SERIALIZE ====================================
    //

    #[\Override]
    public function jsonSerialize(): mixed;

    //
    // ===== ITERATION =========================================
    //

    /**
     * @return \Traversable<int, V>
     */
    #[\Override]
    public function getIterator(): \Traversable;

    //
    // ===== INTERSECT =========================================
    //

    /**
     * @param iterable<V> $other
     */
    public function intersect(iterable $other): static;

    //
    // ===== ACCESSORS =========================================
    //

    //
    // ===== MAX ===============================================
    //

    /**
     * @template OutV
     *
     * @param null|(callable(V): OutV)|(\Closure(V): OutV) $callable
     *
     * @return ($callable is null ? V : OutV)
     */
    public function max(callable|\Closure|null $callable = null): mixed;

    //
    // ===== FILTER ============================================
    //

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $filter
     */
    public function filter(callable|\Closure $filter): static;

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $filter
     */
    public function filterNot(callable|\Closure $filter): static;

    //
    // ===== SINGLE ============================================
    //

    /**
     * @return V
     */
    public function single(): mixed;

    //
    // ===== RANDOM ============================================
    //

    /**
     * @return V
     */
    public function random(): mixed;

    //
    // ===== MAP ===============================================
    //

    /**
     * @param (callable(V): V)|(\Closure(V): V) $function
     */
    public function map(callable|\Closure $function): static;

    //
    // ===== MAP FROM ==========================================
    //

    /**
     * @template InV
     * @template InK
     * @template OutV of V
     *
     * @param iterable<InK, InV>                                    $source
     * @param (callable(InV, InK): OutV)|(\Closure(InV, InK): OutV) $mapFunction
     *
     * @return static<OutV>
     */
    public static function mapFrom(iterable $source, callable|\Closure $mapFunction): static;

    //
    // ===== MAP INTO ==========================================
    //

    /**
     * @template OutV of object|scalar|null
     * @template Out of Set<OutV>
     *
     * @param (callable(V): OutV)|(\Closure(V): OutV) $function
     * @param Out                                     $target
     *
     * @return Out
     */
    public function mapInto(callable|\Closure $function, self $target): self;

    //
    // ===== GET ARRAY =========================================
    //

    /**
     * @return list<V>
     */
    public function getValuesArray(): array;

    //
    // ===== ANY ===============================================
    //

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function any(callable|\Closure $testFunction): bool;

    //
    // ===== ALL ===============================================
    //

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function all(callable|\Closure $testFunction): bool;

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
}
