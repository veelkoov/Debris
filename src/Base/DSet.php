<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Exception\NoSingleElementException;

/**
 * @template V of object|scalar|null
 *
 * @implements Set<V>
 */
class DSet implements Set
{
    /**
     * @var Map<V, null>
     */
    private Map $items;

    /**
     * @param iterable<V> $items
     */
    final public function __construct(iterable $items = [], bool $frozen = false)
    {
        $this->items = self::getNewInternalContainer();

        $this->addAll($items);

        if ($frozen) {
            $this->items->freeze();
        }
    }

    public static function of(mixed ...$items): static
    {
        return new static($items);
    }

    public static function fromUnsafe(mixed $iterable): static
    {
        if (!is_iterable($iterable)) {
            throw new \InvalidArgumentException('Expected an iterable.');
        }

        return new static((static function () use ($iterable) {
            foreach ($iterable as $value) {
                if (!static::isValidValue($value)) {
                    throw new \InvalidArgumentException('Illegal value type.');
                }

                yield $value;
            }
        })());
    }

    public function freeze(): static
    {
        $this->items->freeze();

        return $this;
    }

    #[\Override]
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    #[\Override]
    public function isNotEmpty(): bool
    {
        return $this->items->isNotEmpty();
    }

    #[\Override]
    public function count(): int
    {
        return $this->items->count();
    }

    public function add(mixed ...$value): static
    {
        return $this->addAll($value);
    }

    public function addAll(iterable $values): static
    {
        foreach ($values as $item) {
            $this->items->set($item, null);
        }

        return $this;
    }

    public function plus(mixed ...$value): static
    {
        return $this->plusAll($value);
    }

    public function plusAll(iterable $values): static
    {
        return new static([...$this, ...$values]);
    }

    public function remove(mixed ...$value): static
    {
        return $this->removeAll($value);
    }

    public function removeAll(iterable $values): static
    {
        $this->items->removeAllKeys($values);

        return $this;
    }

    public function minus(mixed ...$value): static
    {
        return $this->minusAll($value);
    }

    public function minusAll(iterable $values): static
    {
        return (new static($this))->removeAll($values);
    }

    public function contains(mixed $value): bool
    {
        return $this->items->hasKey($value);
    }

    public function sorted(callable|\Closure|null $comparator = null, bool $reverse = false): static
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

    #[\Override]
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->getValuesArray());
    }

    public function intersect(iterable $other): static
    {
        $otherValues = [...$other]; // TODO: Optimize for Debris collections

        return self::filter(static fn (mixed $item) => \in_array($item, $otherValues, true));
    }

    public function max(callable|\Closure|null $callable = null): mixed
    {
        if ($this->isEmpty()) {
            throw new EmptyCollectionException('Cannot find max() of an empty set.');
        }

        return max(null === $callable ? $this->getValuesArray() : array_map($callable, $this->getValuesArray())); // @phpstan-ignore argument.type (FIXME)
    }

    public function filter(callable|\Closure $filter): static
    {
        return new static(array_filter($this->getValuesArray(), $filter));
    }

    public function filterNot(callable|\Closure $filter): static
    {
        return $this->filter(static fn (mixed $item) => !$filter($item));
    }

    public function single(): mixed
    {
        try {
            return $this->items->singleKey();
        } catch (NoSingleElementException) {
            throw new NoSingleElementException('The set has '.$this->count().' items instead of exactly one.');
        }
    }

    public function random(): mixed
    {
        if ($this->isEmpty()) {
            throw new EmptyCollectionException('The set is empty.');
        }

        return $this->items->randomKey();
    }

    public function map(callable|\Closure $function): static
    {
        return new static(array_map($function, $this->getValuesArray()));
    }

    public function mapInto(callable|\Closure $function, Set $target): Set
    {
        return $target->addAll(array_map($function, $this->getValuesArray()));
    }

    public static function mapFrom(iterable $source, callable|\Closure $mapFunction): self
    {
        return new static((static function () use ($source, $mapFunction) {
            foreach ($source as $key => $value) {
                yield $mapFunction($value, $key);
            }
        })());
    }

    public function getValuesArray(): array
    {
        return $this->items->getKeysArray();
    }

    public function any(callable|\Closure $testFunction): bool
    {
        return $this->items->anyKey($testFunction);
    }

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

    /**
     * @return Map<V, null>
     */
    protected static function getNewInternalContainer(): Map
    {
        return new DMap();
    }

    /**
     * @param V $value
     *
     * @return V
     */
    protected static function enforceValueType(mixed $value): mixed
    {
        throw new \LogicException('Not implemented. '.__METHOD__.' needs to be overridden.');
    }

    /**
     * @phpstan-assert-if-true V $value
     */
    protected static function isValidValue(mixed $value): bool
    {
        throw new \LogicException('Not implemented. '.__METHOD__.' needs to be overridden.');
    }
}
