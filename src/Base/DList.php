<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Exception\NoSingleElementException;

/**
 * @template V of object|scalar|null
 *
 * @implements Lis<V>
 */
class DList implements Lis
{
    protected readonly Freezer $freezer;

    /**
     * @var list<V>
     */
    protected array $items;

    /**
     * @param iterable<V> $items
     */
    final public function __construct(iterable $items = [], bool $frozen = false)
    {
        $this->items = array_values([...$items]);
        $this->freezer = new Freezer($this, $frozen);
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
        $this->freezer->freeze();

        return $this;
    }

    #[\Override]
    public function isEmpty(): bool
    {
        return [] === $this->items;
    }

    #[\Override]
    public function isNotEmpty(): bool
    {
        return [] !== $this->items;
    }

    #[\Override]
    public function count(): int
    {
        return \count($this->items);
    }

    public function add(mixed ...$value): static
    {
        return $this->addAll($value);
    }

    public function addAll(iterable $values): static
    {
        $this->freezer->protect();

        array_push($this->items, ...$values); // @phpstan-ignore assign.propertyType (FIXME: I'm almost sure this is a false-positive.)

        return $this;
    }

    public function plus(mixed ...$value): static
    {
        return $this->plusAll($value);
    }

    public function plusAll(iterable $values): static
    {
        return new static([...$this->items, ...$values]);
    }

    public function remove(mixed ...$value): static
    {
        return $this->removeAll($value);
    }

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
        return \in_array($value, $this->items, true);
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
        return $this->items;
    }

    #[\Override]
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }

    public function intersect(iterable $other): static
    {
        $otherValues = [...$other]; // TODO: Optimize for Debris collections

        return self::filter(static fn (mixed $item) => \in_array($item, $otherValues, true));
    }

    public function max(callable|\Closure|null $callable = null): mixed
    {
        if ([] === $this->items) {
            throw new EmptyCollectionException('Cannot find max() of an empty list.');
        }

        return max(null === $callable ? $this->items : array_map($callable, $this->items));
    }

    public function at(int $index): mixed // FIXME: Somehow by key?
    {
        return $this->items[$index];
    }

    public function filter(callable|\Closure $filter): static
    {
        return new static(array_filter($this->items, $filter));
    }

    public function filterNot(callable|\Closure $filter): static
    {
        return $this->filter(static fn (mixed $item) => !$filter($item));
    }

    public function single(): mixed
    {
        if (1 !== $this->count()) {
            throw new NoSingleElementException('The list has '.$this->count().' items instead of exactly one.');
        }

        return $this->items[0];
    }

    public function random(): mixed
    {
        if ([] === $this->items) {
            throw new EmptyCollectionException('The list is empty.');
        }

        return $this->at(array_rand($this->items));
    }

    public function map(callable|\Closure $function): static
    {
        return new static(array_map($function, $this->items));
    }

    public function mapInto(callable|\Closure $function, Lis $target): Lis
    {
        return $target->addAll(array_map($function, $this->items));
    }

    public static function mapFrom(iterable $source, callable|\Closure $mapFunction): static
    {
        return new static((static function () use ($source, $mapFunction) {
            foreach ($source as $key => $value) {
                yield $mapFunction($value, $key);
            }
        })());
    }

    public function getValuesArray(): array
    {
        return $this->items;
    }

    public function any(callable|\Closure $testFunction): bool
    {
        foreach ($this->items as $value) {
            if ($testFunction($value)) {
                return true;
            }
        }

        return false;
    }

    public function all(callable|\Closure $testFunction): bool
    {
        foreach ($this->items as $value) {
            if (!$testFunction($value)) {
                return false;
            }
        }

        return true;
    }

    public function shuffle(): static
    {
        $result = new static($this->items);
        shuffle($result->items);

        return $result;
    }

    public function slice(int $offset, ?int $length = null): static
    {
        return new static(\array_slice($this->items, $offset, $length));
    }

    public function unique(): static
    {
        return new static(array_unique($this->items, SORT_REGULAR));
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
