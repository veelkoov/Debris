<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use IteratorAggregate;

/**
 * @template T of object
 *
 * @implements IteratorAggregate<int, T>
 */
class DObjectSet implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @var \SplObjectStorage<T, null>
     */
    protected \SplObjectStorage $items;

    private bool $frozen;

    /**
     * @param iterable<T> $items
     */
    final public function __construct(iterable $items = [])
    {
        $this->items = new \SplObjectStorage();
        $this->frozen = false;
        $this->addAll($items);
        $this->frozen = true;
    }

    /**
     * @param T ...$items
     */
    public static function of(mixed ...$items): static
    {
        return new static($items);
    }

    /**
     * @param iterable<T> $items
     */
    public static function mut(iterable $items = []): static
    {
        $result = new static($items);
        $result->frozen = false;

        return $result;
    }

    public function frozen(): static
    {
        $result = clone $this;
        $result->frozen = true;

        return $result;
    }

    /**
     * @phpstan-assert-if-true empty $this->toArray()
     */
    public function isEmpty(): bool
    {
        return 0 === \count($this->items);
    }

    /**
     * @phpstan-assert-if-true non-empty-array<T> $this->toArray()
     */
    public function isNotEmpty(): bool
    {
        return 0 !== \count($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @param T ...$items
     */
    public function add(mixed ...$items): static
    {
        return $this->addAll($items);
    }

    /**
     * @param iterable<T> $items
     */
    public function addAll(iterable $items): static
    {
        if ($this->frozen) {
            throw new ChangingImmutableException(__CLASS__);
        }

        foreach ($items as $item) {
            $this->items->attach($item);
        }

        return $this;
    }

    /**
     * @param iterable<T> $items
     */
    public function plusAll(iterable $items): static
    {
        return new static([...$this, ...$items]);
    }

    /**
     * @param T ...$item
     */
    public function plus(mixed ...$item): static
    {
        return $this->plusAll($item);
    }

    /**
     * @param T ...$items
     */
    public function remove(mixed ...$items): static
    {
        return $this->removeAll($items);
    }

    /**
     * @param iterable<T> $items
     */
    public function removeAll(iterable $items): static
    {
        if ($this->frozen) {
            throw new ChangingImmutableException(__CLASS__);
        }

        foreach ($items as $item) {
            $this->items->detach($item);
        }

        return $this;
    }

    /**
     * @param T $item
     */
    public function contains(mixed $item): bool
    {
        return $this->items->contains($item);
    }

    /**
     * @template TResult
     *
     * @param ?callable(T): TResult $callable
     *
     * @return ($callable is null ? T : TResult)
     */
    public function max(?callable $callable = null): mixed
    {
        if ($this->isEmpty()) {
            throw new EmptyCollectionException('Cannot find max() of an empty set.');
        }

        return max(null === $callable ? $this->toArray() : array_map($callable, $this->toArray()));
    }

    public function filter(callable $filterFunction): static
    {
        return new static(array_filter($this->toArray(), $filterFunction));
    }

    /**
     * @return \Traversable<int, T>
     */
    #[\Override]
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * @return list<T>
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this->items as $item) {
            $result[] = $item;
        }

        return $result;
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
