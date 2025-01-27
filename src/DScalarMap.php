<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use IteratorAggregate;

/**
 * @template K of array-key
 * @template V
 *
 * @implements IteratorAggregate<K, V>
 */
class DScalarMap implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @var array<K, V>
     */
    protected array $items;

    private bool $frozen = true;

    /**
     * @param iterable<K, V> $items
     */
    final public function __construct(iterable $items = [])
    {
        $this->items = [...$items];
    }

    /**
     * @param iterable<K, V> $items
     */
    public static function mut(iterable $items = []): static
    {
        $result = new static($items);
        $result->frozen = false;

        return $result;
    }

    public function frozen(): static
    {
        return new static($this->items);
    }

    /**
     * @phpstan-assert-if-true !non-empty-array<K, V> $this->items
     */
    public function isEmpty(): bool
    {
        return [] === $this->items;
    }

    /**
     * @phpstan-assert-if-true non-empty-array<K, V> $this->items
     */
    public function isNotEmpty(): bool
    {
        return [] !== $this->items;
    }

    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @param K $key
     * @param V $value
     */
    public function set(int|string $key, mixed $value): static
    {
        if ($this->frozen) {
            throw new ChangingImmutableException(__CLASS__);
        }

        $this->items[$key] = $value;

        return $this;
    }

    /**
     * @param K             $key
     * @param callable(): V $newValueFunction
     *
     * @return V
     */
    public function getOrSet(int|string $key, callable $newValueFunction): mixed
    {
        return $this->items[$key] ??= $newValueFunction();
    }

    /**
     * @template T
     *
     * @param K                 $key
     * @param (callable(): T)|T $default
     *
     * @return T|V
     */
    public function getOrDefault(int|string $key, mixed $default): mixed
    {
        return \array_key_exists($key, $this->items) ? $this->items[$key] : (\is_callable($default) ? $default() : $default);
    }

    /**
     * @param V $value
     */
    public function contains(mixed $value): bool
    {
        return \in_array($value, $this->items, true);
    }

    /**
     * @param K $key
     */
    public function hasKey(int|string $key): bool
    {
        return \array_key_exists($key, $this->items);
    }

    /**
     * @param callable(K, V): bool $function
     */
    public function filter(callable $function): static
    {
        return new static(array_filter($this->items, static fn ($value, $key) => $function($key, $value), ARRAY_FILTER_USE_BOTH));
    }

    /**
     * @param callable(V): bool $function
     */
    public function filterValues(callable $function): static
    {
        return new static(array_filter($this->items, static fn ($value) => $function($value)));
    }

    /**
     * @return \Traversable<K, V>
     */
    #[\Override]
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return $this->items;
    }
}
