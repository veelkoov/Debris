<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets\Base;

use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Set;

/**
 * @template V of object|scalar|null
 */
trait EverySetTrait
{
    #[\Override]
    public static function of(mixed ...$items): static
    {
        return new static($items);
    }

    #[\Override]
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

    #[\Override]
    public function add(mixed ...$value): static
    {
        return $this->addAll($value);
    }

    #[\Override]
    public function plus(mixed ...$value): static
    {
        return $this->plusAll($value);
    }

    #[\Override]
    public function plusAll(iterable $values): static
    {
        return new static([...$this, ...$values]);
    }

    #[\Override]
    public function remove(mixed ...$value): static
    {
        return $this->removeAll($value);
    }

    #[\Override]
    public function minus(mixed ...$value): static
    {
        return $this->minusAll($value);
    }

    #[\Override]
    public function minusAll(iterable $values): static
    {
        return (new static($this))->removeAll($values);
    }

    #[\Override]
    public function shuffle(): static
    {
        $items = $this->getValuesArray();
        shuffle($items);

        return new static($items);
    }

    #[\Override]
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

    #[\Override]
    public function intersect(iterable $other): static
    {
        $otherValues = [...$other]; // TODO: Optimize for Debris collections

        return self::filter(static fn (mixed $item) => \in_array($item, $otherValues, true));
    }

    #[\Override]
    public function max(callable|\Closure|null $callable = null): mixed
    {
        if ($this->isEmpty()) {
            throw new EmptyCollectionException('Cannot find max() of an empty set.');
        }

        return max(null === $callable ? $this->getValuesArray() : array_map($callable, $this->getValuesArray())); // @phpstan-ignore argument.type (FIXME)
    }

    #[\Override]
    public function filter(callable|\Closure $filter): static
    {
        return new static(array_filter($this->getValuesArray(), $filter));
    }

    #[\Override]
    public function filterNot(callable|\Closure $filter): static
    {
        return $this->filter(static fn (mixed $item) => !$filter($item));
    }

    #[\Override]
    public function map(callable|\Closure $function): static
    {
        return new static(array_map($function, $this->getValuesArray()));
    }

    #[\Override]
    public function mapInto(callable|\Closure $function, Set $target): Set
    {
        return $target->addAll(array_map($function, $this->getValuesArray()));
    }

    #[\Override]
    public static function mapFrom(iterable $source, callable|\Closure $mapFunction): static
    {
        return new static((static function () use ($source, $mapFunction) {
            foreach ($source as $key => $value) {
                yield $mapFunction($value, $key);
            }
        })());
    }
}
