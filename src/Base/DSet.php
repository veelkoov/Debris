<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Exception\NoSingleElementException;

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
     * @param null|(callable(V, V): int)|(\Closure(V, V): int) $comparator
     */
    public function sorted(null|callable|\Closure $comparator = null, bool $reverse = false): static
    {
        $times = $reverse ? -1 : 1;
        $comparator ??= static fn (mixed $a, mixed $b): int => $a <=> $b;

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
     * @param iterable<V> $other
     */
    public function intersect(iterable $other): static
    {
        $otherValues = [...$other]; // TODO: Optimize for Debris collections

        return self::filter(static fn (mixed $item) => \in_array($item, $otherValues, true));
    }

    /**
     * @template TResult
     *
     * @param null|(callable(V): TResult)|(\Closure(V): TResult) $callable
     *
     * @return ($callable is null ? V : TResult)
     */
    public function max(null|callable|\Closure $callable = null): mixed
    {
        if ($this->isEmpty()) {
            throw new EmptyCollectionException('Cannot find max() of an empty set.');
        }

        return max(null === $callable ? $this->getValuesArray() : array_map($callable, $this->getValuesArray())); // @phpstan-ignore argument.type (FIXME)
    }

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $filterFunction
     */
    public function filter(callable|\Closure $filterFunction): static
    {
        return new static(array_filter($this->getValuesArray(), $filterFunction));
    }

    /**
     * @return V
     */
    public function single(): mixed
    {
        try {
            return $this->items->singleKey();
        } catch (NoSingleElementException) {
            throw new NoSingleElementException('The set has '.$this->count().' items instead of exactly one.');
        }
    }

    /**
     * @param (callable(V): V)|(\Closure(V): V) $mapFunction
     */
    public function map(callable|\Closure $mapFunction): static
    {
        return new static(array_map($mapFunction, $this->getValuesArray()));
    }

    /**
     * @template InV
     * @template InK
     * @template OutV of object|scalar|null
     *
     * @param iterable<InK, InV>                                    $source
     * @param (callable(InV, InK): OutV)|(\Closure(InV, InK): OutV) $mapFunction
     *
     * @return static<OutV>
     */
    public static function mapFrom(iterable $source, callable|\Closure $mapFunction): self
    {
        return new static((static function () use ($source, $mapFunction) {
            foreach ($source as $key => $value) {
                yield $mapFunction($value, $key);
            }
        })());
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

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function any(callable|\Closure $testFunction): bool
    {
        return $this->items->anyKey($testFunction);
    }

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function all(callable|\Closure $testFunction): bool
    {
        return $this->items->allKeys($testFunction);
    }

    public function shuffle(): static
    {
        $items = $this->getValuesArray();
        shuffle($items);

        return new static($items);
    }

    public function slice(int $offset, ?int $length = null): static
    {
        return new static(\array_slice($this->getValuesArray(), $offset, $length));
    }
}
