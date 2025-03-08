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
class DList implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @var list<V>
     */
    protected array $items;

    protected readonly Freezer $freezer;

    /**
     * @param iterable<V> $items
     */
    final public function __construct(iterable $items = [], bool $frozen = false)
    {
        $this->items = array_values([...$items]);
        $this->freezer = new Freezer($this, $frozen);
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
        return [] === $this->items;
    }

    public function isNotEmpty(): bool
    {
        return [] !== $this->items;
    }

    public function count(): int
    {
        return \count($this->items);
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

        array_push($this->items, ...$values);

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
        return new static([...$this->items, ...$values]);
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

        $result = $this->items;

        foreach ($values as $item) {
            $key = array_search($item, $result, true);

            if (false === $key) {
                continue;
            }

            unset($result[$key]);
        }

        $this->items = array_values($result);

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
        return \in_array($value, $this->items, true);
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
        return $this->items;
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
     * @return \Traversable<int, V>
     */
    #[\Override]
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return list<V>
     */
    public function getValuesArray(): array
    {
        return $this->items;
    }

    /**
     * @param (callable(V): V)|(\Closure(V): V) $function
     */
    public function map(callable|\Closure $function): static
    {
        return new static(array_map($function, $this->items));
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
     * @template OutV
     *
     * @param null|(callable(V): OutV)|(\Closure(V): OutV) $callable
     *
     * @return ($callable is null ? V : OutV)
     */
    public function max(null|callable|\Closure $callable = null): mixed
    {
        if ([] === $this->items) {
            throw new EmptyCollectionException('Cannot find max() of an empty list.');
        }

        return max(null === $callable ? $this->items : array_map($callable, $this->items));
    }

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $filterFunction
     */
    public function filter(callable|\Closure $filterFunction): static
    {
        return new static(array_filter($this->items, $filterFunction));
    }

    /**
     * @return V
     */
    public function single(): mixed
    {
        if (1 !== $this->count()) {
            throw new NoSingleElementException('The list has '.$this->count().' items instead of exactly one.');
        }

        return $this->items[0];
    }

    /**
     * @return V
     */
    public function at(int $index): mixed
    {
        return $this->items[$index];
    }

    public function unique(): static
    {
        return new static(array_unique($this->items, SORT_REGULAR));
    }

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function any(callable|\Closure $testFunction): bool
    {
        foreach ($this->items as $value) {
            if ($testFunction($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param (callable(V): bool)|(\Closure(V): bool) $testFunction
     */
    public function all(callable|\Closure $testFunction): bool
    {
        foreach ($this->items as $value) {
            if (!$testFunction($value)) {
                return false;
            }
        }

        return true;
    }
}
