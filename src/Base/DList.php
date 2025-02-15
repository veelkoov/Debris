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
    final public function __construct(iterable $items = [], bool $frozen = true)
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
     * @param iterable<V> $items
     */
    public static function mut(iterable $items = []): static
    {
        return new static($items, frozen: false);
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
        $this->freezer->protect();

        array_push($this->items, ...$items);

        return $this;
    }

    /**
     * @param V $item
     */
    public function contains(mixed $item): bool
    {
        return \in_array($item, $this->items, true);
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
     * @param V $item
     */
    public function plus(mixed $item): static
    {
        return new static([...$this->items, $item]);
    }

    /**
     * @param V[] $items
     */
    public function plusAll(iterable $items): static
    {
        return new static([...$this->items, ...$items]);
    }

    /**
     * @param iterable<V> $items
     */
    public function minusAll(iterable $items): static
    {
        $result = $this->items;

        foreach ($items as $item) {
            $key = array_search($item, $result, true);

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
