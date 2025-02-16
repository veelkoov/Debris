<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use IteratorAggregate;
use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Exception\NoSingleElementException;

/**
 * @template V
 *
 * @implements IteratorAggregate<int, V>
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
        $this->freezer = new Freezer($this, $frozen);
        $this->items = array_values([...$items]);
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
     * @param V $value
     */
    public function contains(mixed $value): bool
    {
        return \in_array($value, $this->items, true);
    }

    public function sorted(?\Closure $function = null): static
    {
        $result = clone $this;

        if (null === $function) {
            sort($result->items);
        } else {
            usort($result->items, $function);
        }

        return $result;
    }

    public function unique(): static
    {
        return new static(array_unique($this->items, SORT_REGULAR));
    }

    /**
     * @param V $value
     */
    public function plus(mixed $value): static
    {
        return new static([...$this->items, $value]);
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
        $result = $this->items;

        foreach ($values as $value) {
            $key = array_search($value, $result, true);

            if (false === $key) {
                continue;
            }

            unset($result[$key]);
        }

        return new static($result);
    }

    /**
     * @param static $other
     */
    public function intersect(mixed $other): static
    {
        return self::filter(static fn (mixed $item) => \in_array($item, $other->items, true));
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
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @template SourceT
     *
     * @param iterable<SourceT>    $source
     * @param callable(SourceT): V $mapFunction
     */
    public static function mapFrom(iterable $source, callable $mapFunction): static
    {
        $result = [];

        foreach ($source as $value) {
            $result[] = $mapFunction($value);
        }

        return new static($result);
    }

    /**
     * @param callable(V): V $mapFunction
     */
    public function map(callable $mapFunction): static
    {
        return new static(array_map($mapFunction, $this->items));
    }

    /**
     * @template SourceK
     * @template SourceV
     *
     * @param iterable<SourceK, SourceV>    $source
     * @param callable(SourceK, SourceV): V $mapFunction
     */
    public static function mapWithKey(iterable $source, callable $mapFunction): static
    {
        $result = [];

        foreach ($source as $key => $value) {
            $result[] = $mapFunction($key, $value);
        }

        return new static($result);
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
        if ([] === $this->items) {
            throw new EmptyCollectionException('Cannot find max() of an empty list.');
        }

        return max(null === $callable ? $this->items : array_map($callable, $this->items));
    }

    public function filter(callable $filterFunction): static
    {
        return new static(array_filter($this->items, $filterFunction));
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return $this->items;
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
}
