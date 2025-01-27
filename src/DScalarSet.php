<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Override;
use Traversable;

/**
 * @template T of array-key
 *
 * @implements IteratorAggregate<int, T>
 */
class DScalarSet implements IteratorAggregate, JsonSerializable
{
    /**
     * @var array<T, null>
     */
    protected array $items;

    private bool $frozen = true;

    /**
     * @param iterable<T> $items
     */
    final public function __construct(iterable $items = [])
    {
        $this->items = array_fill_keys([...$items], null);
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
        $result = clone $this;
        $result->frozen = true;

        return $result;
    }

    /**
     * @phpstan-assert-if-true !non-empty-array<T, null> $this->items
     */
    public function isEmpty(): bool
    {
        return [] === $this->items;
    }

    /**
     * @phpstan-assert-if-true non-empty-array<T, null> $this->items
     */
    public function isNotEmpty(): bool
    {
        return [] !== $this->items;
    }

    public function count(): int
    {
        return count($this->items);
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

        foreach ($items as $item) {
            $this->items[$item] = null;
        }

        return $this;
    }

    /**
     * @param iterable<T> $items
     */
    public function plusAll(iterable $items): static
    {
        return new static([...array_keys($this->items), ...$items]);
    }

    /**
     * @param T $item
     */
    public function plus(mixed $item): static
    {
        return $this->plusAll([$item]);
    }

    /**
     * @param T ...$items
     */
    public function remove(mixed ...$items): static
    {
        return $this->removeAll($items);
    }

    /**
     * @param iterable<T> $items
     */
    public function removeAll(iterable $items): static
    {
        if ($this->frozen) {
            throw new ChangingImmutableException(__CLASS__);
        }

        foreach ($items as $item) {
            unset($this->items[$item]);
        }

        return $this;
    }

    /**
     * @param T $item
     */
    public function contains(mixed $item): bool
    {
        return array_key_exists($item, $this->items);
    }

    /**
     * @return Traversable<int, T>
     */
    #[Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * @return list<T>
     */
    public function toArray(): array
    {
        return array_keys($this->items);
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
