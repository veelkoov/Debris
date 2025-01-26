<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use RuntimeException;

/**
 * @template T of array-key
 */
class DScalarSet
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
            throw new RuntimeException('Tried to modify immutable ' . __CLASS__);
        }

        foreach ($items as $item) {
            $this->items[$item] = null;
        }

        return $this;
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
            throw new RuntimeException('Tried to modify immutable ' . __CLASS__);
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
     * @return list<T>
     */
    public function toArray(): array
    {
        return array_keys($this->items);
    }
}
