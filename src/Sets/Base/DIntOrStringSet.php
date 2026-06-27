<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets\Base;

use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Exception\NoSingleElementException;
use Veelkoov\Debris\Internal\Freezer;
use Veelkoov\Debris\Set;

/**
 * @template V of int|string
 *
 * @implements Set<V>
 */
class DIntOrStringSet implements Set
{
    /**
     * @use EverySetTrait<V>
     */
    use EverySetTrait;

    protected readonly Freezer $freezer;

    /**
     * @var array<V, V>
     */
    private array $items = [];

    /**
     * @param iterable<V> $items
     */
    final public function __construct(iterable $items = [], bool $frozen = false)
    {
        $this->freezer = new Freezer($this, false);

        $this->addAll($items);

        if ($frozen) {
            $this->freezer->freeze();
        }
    }

    #[\Override]
    public function freeze(): static
    {
        $this->freezer->freeze();

        return $this;
    }

    #[\Override]
    public function isEmpty(): bool
    {
        return 0 === \count($this->items);
    }

    #[\Override]
    public function isNotEmpty(): bool
    {
        return 0 !== \count($this->items);
    }

    #[\Override]
    public function count(): int
    {
        return \count($this->items);
    }

    #[\Override]
    public function addAll(iterable $values): static
    {
        $this->freezer->protect();

        foreach ($values as $item) {
            $this->items[$item] = $item;
        }

        return $this;
    }

    #[\Override]
    public function removeAll(iterable $values): static
    {
        $this->freezer->protect();

        foreach ($values as $item) {
            unset($this->items[$item]);
        }

        return $this;
    }

    #[\Override]
    public function contains(mixed $value): bool
    {
        return \array_key_exists($value, $this->items);
    }

    #[\Override]
    public function single(): mixed
    {
        if (1 !== \count($this->items)) {
            throw new NoSingleElementException('The set has '.$this->count().' items instead of exactly one.');
        }

        return array_key_first($this->items);
    }

    #[\Override]
    public function random(): mixed
    {
        if ([] === $this->items) {
            throw new EmptyCollectionException('The set is empty.');
        }

        return $this->items[array_rand($this->items)];
    }

    #[\Override]
    public function getValuesArray(): array
    {
        return array_values($this->items);
    }

    #[\Override]
    public function any(callable|\Closure $testFunction): bool
    {
        foreach ($this->items as $value) {
            if ($testFunction($value)) {
                return true;
            }
        }

        return false;
    }

    #[\Override]
    public function all(callable|\Closure $testFunction): bool
    {
        foreach ($this->items as $value) {
            if (!$testFunction($value)) {
                return false;
            }
        }

        return true;
    }

    #[\Override]
    public function slice(int $offset, ?int $length = null): static
    {
        return new static(\array_slice($this->items, $offset, $length));
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
