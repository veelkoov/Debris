<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Exception\EmptyCollectionException;

/**
 * @template V of object|scalar|null
 *
 * @implements \IteratorAggregate<V>
 */
class DSet implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @var DMap<V, null>
     */
    private DMap $items;

    // TODO private bool $frozen;

    /**
     * @param iterable<V> $items
     */
    final public function __construct(iterable $items = [])
    {
        $this->items = new DMap();

        foreach ($items as $item) {
            $this->items->set($item, null);
        }
    }

    /**
     * @param V ...$items
     */
    public static function of(mixed ...$items): static
    {
        return new static($items);
    }

    /**
     * @param iterable<V> $items
     */
    public static function mut(iterable $items = []): static
    {
        $result = new static($items);
        //        $result->frozen = false;

        return $result;
    }

    public function frozen(): static
    {
        return clone $this;
        //        $result->frozen = true;
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    public function isNotEmpty(): bool
    {
        return $this->items->isNotEmpty();
    }

    public function count(): int
    {
        return $this->items->count();
    }

    /**
     * @param V ...$items
     */
    public function add(mixed ...$items): static
    {
        return $this->addAll($items);
    }

    /**
     * @param iterable<V> $items
     */
    public function addAll(iterable $items): static
    {
        // if ($this->frozen) {
        //     throw new ChangingImmutableException(__CLASS__);
        // }

        foreach ($items as $item) {
            $this->items->set($item, null);
        }

        return $this;
    }

    /**
     * @param iterable<V> $items
     */
    public function plusAll(iterable $items): static
    {
        return new static([...$this, ...$items]);
    }

    /**
     * @param V ...$item
     */
    public function plus(mixed ...$item): static
    {
        return $this->plusAll($item);
    }

    /**
     * @param V ...$items
     */
    public function remove(mixed ...$items): static
    {
        return $this->removeAll($items);
    }

    /**
     * @param iterable<V> $items
     */
    public function removeAll(iterable $items): static
    {
        // if ($this->frozen) {
        //     throw new ChangingImmutableException(__CLASS__);
        // }

        foreach ($items as $item) {
            $this->items->removeKey($item);
        }

        return $this;
    }

    public function minus(): void
    {
        // TODO
    }

    public function minusAll(): void
    {
        // TODO
    }

    /**
     * @param V $item
     */
    public function contains(mixed $item): bool
    {
        return $this->items->hasKey($item);
    }

    /**
     * @template TResult
     *
     * @param ?callable(V): TResult $callable
     *
     * @return ($callable is null ? V : TResult)
     */
    public function max(?callable $callable = null): mixed
    {
        if ($this->isEmpty()) {
            throw new EmptyCollectionException('Cannot find max() of an empty set.');
        }

        return max(null === $callable ? $this->getValuesArray() : array_map($callable, $this->getValuesArray())); // @phpstan-ignore argument.type (FIXME)
    }

    public function filter(callable $filterFunction): static
    {
        return new static(array_filter($this->getValuesArray(), $filterFunction));
    }

    /**
     * @param callable(V): V $mapFunction
     */
    public function map(callable $mapFunction): static
    {
        return new static(array_map($mapFunction, $this->getValuesArray()));
    }

    /**
     * @return list<V>
     */
    public function getValuesArray(): array
    {
        return $this->items->getKeysArray();
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->getValuesArray());
    }

    public function jsonSerialize(): mixed
    {
        return $this->getValuesArray();
    }
}
