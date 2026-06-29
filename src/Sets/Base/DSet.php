<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets\Base;

use Veelkoov\Debris\Exception\EmptyCollectionException;
use Veelkoov\Debris\Exception\NoSingleElementException;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\Base\DMap;
use Veelkoov\Debris\Set;

/**
 * @template V of object|scalar|null
 *
 * @implements Set<V>
 */
class DSet implements Set
{
    /**
     * @use EverySetTrait<V>
     */
    use EverySetTrait;

    /**
     * @var Map<V, null>
     */
    private Map $items;

    /**
     * @param iterable<V> $items
     */
    final public function __construct(iterable $items = [], bool $frozen = false)
    {
        $this->items = new DMap();

        $this->addAll($items);

        if ($frozen) {
            $this->items->freeze();
        }
    }

    #[\Override]
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

    #[\Override]
    public function addAll(iterable $values): static
    {
        foreach ($values as $item) {
            $this->items->set($item, null);
        }

        return $this;
    }

    #[\Override]
    public function removeAll(iterable $values): static
    {
        $this->items->removeAllKeys($values);

        return $this;
    }

    #[\Override]
    public function contains(mixed $value): bool
    {
        return $this->items->hasKey($value);
    }

    #[\Override]
    public function single(): mixed
    {
        try {
            return $this->items->singleKey();
        } catch (NoSingleElementException) {
            throw new NoSingleElementException('The set has '.$this->count().' items instead of exactly one.');
        }
    }

    #[\Override]
    public function random(): mixed
    {
        if ($this->isEmpty()) {
            throw new EmptyCollectionException('The set is empty.');
        }

        return $this->items->randomKey();
    }

    #[\Override]
    public function getValuesArray(): array
    {
        return $this->items->getKeysArray();
    }

    #[\Override]
    public function any(callable|\Closure $testFunction): bool
    {
        return $this->items->anyKey($testFunction);
    }

    #[\Override]
    public function all(callable|\Closure $testFunction): bool
    {
        return $this->items->allKeys($testFunction);
    }

    #[\Override]
    public function slice(int $offset, ?int $length = null): static
    {
        return new static(\array_slice($this->getValuesArray(), $offset, $length));
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
