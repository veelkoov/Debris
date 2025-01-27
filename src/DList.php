<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use IteratorAggregate;

/**
 * @template T
 *
 * @implements IteratorAggregate<int, T>
 */
class DList implements \IteratorAggregate, \JsonSerializable
{
    /**
     * @var list<T>
     */
    protected array $items;

    private bool $frozen = true;

    /**
     * @param iterable<T> $items
     */
    final public function __construct(iterable $items = [])
    {
        $this->items = array_values([...$items]);
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
        return new static($this->items);
    }

    /**
     * @phpstan-assert-if-true !non-empty-array<T> $this->items
     */
    public function isEmpty(): bool
    {
        return [] === $this->items;
    }

    /**
     * @phpstan-assert-if-true non-empty-array<T> $this->items
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

        array_push($this->items, ...$items);

        return $this;
    }

    /**
     * @param T $item
     */
    public function contains(mixed $item): bool
    {
        return \in_array($item, $this->items, true);
    }

    public function each(\Closure $closure): static
    {
        foreach ($this->items as $item) {
            $closure($item);
        }

        return $this;
    }

    public function sortInPlace(?\Closure $function = null): static
    {
        if (null === $function) {
            sort($this->items);
        } else {
            usort($this->items, $function);
        }

        return $this;
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
     * @param T[] $items
     */
    public function plusAll(iterable $items): static
    {
        return new static([...$this->items, ...$items]);
    }

    /**
     * @param T $item
     */
    public function plus(mixed $item): static
    {
        return new static([...$this->items, $item]);
    }

    /**
     * @param T[] $items
     */
    public function minusAll(array $items): static
    {
        return (new static($this->items))->filter(static fn ($item) => !\in_array($item, $items, true));
    }

    /**
     * @param static $other
     */
    public function intersect(mixed $other): static
    {
        return self::filter(static fn (mixed $item) => \in_array($item, $other->items, true));
    }

    /**
     * @return \Traversable<int, T>
     */
    #[\Override]
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return list<T>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @template SourceT
     *
     * @param iterable<SourceT>    $source
     * @param callable(SourceT): T $mapFunction
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
     * @param callable(T): T $mapFunction
     */
    public function map(callable $mapFunction): static
    {
        return new static(array_map($mapFunction, $this->items));
    }

    /**
     * @template SourceK
     * @template SourceV
     *
     * @param array<SourceK, SourceV>       $source
     * @param callable(SourceK, SourceV): T $mapFunction
     */
    public static function mapWithKey(array $source, callable $mapFunction): static
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
     * @param ?callable(T): TResult $callable
     *
     * @return ($callable is null ? T : TResult)
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
     * @return T
     */
    public function single(): mixed
    {
        if (1 !== $this->count()) {
            throw new NoSingleElementException('The list has '.$this->count().' items instead of exactly one.');
        }

        return $this->items[0];
    }

    /**
     * @param static $other
     */
    public function sameElements(mixed $other): bool
    {
        return $this->count() === $other->count() && $this->sorted()->items === $other->sorted()->items;
    }

    /**
     * @return T
     */
    public function at(int $index): mixed
    {
        return $this->items[$index];
    }
}
