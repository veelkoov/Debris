<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Exception\EmptyCollectionException;

/**
 * @template V of object|scalar|null
 *
 * @implements \IteratorAggregate<int, V>
 */
class DSet implements \IteratorAggregate, \JsonSerializable
{
    protected readonly Freezer $freezer;

    /**
     * @var DMap<V, null>
     */
    private DMap $items;

    /**
     * @param iterable<V> $items
     */
    final public function __construct(iterable $items = [], bool $frozen = false)
    {
        $this->items = new DMap();
        $this->freezer = new Freezer($this, false);

        $this->addAll($items);

        if ($frozen) {
            $this->freezer->freeze();
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
     * @return $this
     */
    public function freeze(): static
    {
        $this->freezer->freeze();

        return $this;
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
     * @param V ...$value
     *
     * @return $this
     */
    public function add(mixed ...$value): static
    {
        return $this->addAll($value);
    }

    /**
     * @param iterable<V> $values
     *
     * @return $this
     */
    public function addAll(iterable $values): static
    {
        $this->freezer->protect();

        foreach ($values as $item) {
            $this->items->set($item, null);
        }

        return $this;
    }

    /**
     * @param V ...$value
     */
    public function plus(mixed ...$value): static
    {
        return $this->plusAll($value);
    }

    /**
     * @param iterable<V> $values
     */
    public function plusAll(iterable $values): static
    {
        return new static([...$this, ...$values]);
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

        $this->items->unsetAll($values);

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
        return $this->items->hasKey($value);
    }

    /**
     * @param callable(V, V): int $comparator
     */
    public function sorted(callable $comparator, bool $reverse = false): static
    {
        $times = $reverse ? -1 : 1;

        $values = $this->getValuesArray();
        usort($values, static fn (mixed $value1, mixed $value2): int => $times * $comparator($value1, $value2));

        return new static($values);
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return $this->getValuesArray();
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
}
