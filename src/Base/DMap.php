<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Base\Internal\MapKey;
use Veelkoov\Debris\Base\Internal\MapKeyMapper;
use Veelkoov\Debris\Exception\MissingKeyException;
use Veelkoov\Debris\Exception\NoSingleElementException;
use Veelkoov\Debris\Maps\Pair;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 *
 * @implements Map<K, V>
 */
class DMap implements Map
{
    /**
     * @use EveryMapTrait<K, V>
     */
    use EveryMapTrait;

    protected readonly Freezer $freezer;

    /**
     * @var \SplObjectStorage<MapKey<K>, V>
     */
    protected \SplObjectStorage $items;

    /**
     * @var MapKeyMapper<K>
     */
    protected readonly MapKeyMapper $mappedKeys;

    /**
     * @param iterable<K, V> $items
     */
    final public function __construct(iterable $items = [], bool $frozen = false)
    {
        $this->mappedKeys = new MapKeyMapper();
        $this->items = new \SplObjectStorage();
        $this->freezer = new Freezer($this, false);

        $this->setAll($items);

        if ($frozen) {
            $this->freezer->freeze();
        }
    }

    #[\Override]
    public function getKeysArray(): array
    {
        $result = [];

        foreach ($this->items as $key) {
            $result[] = $key->key;
        }

        return $result;
    }

    #[\Override]
    public function getValuesArray(): array
    {
        $result = [];

        foreach ($this->items as $key) {
            $result[] = $this->items[$key];
        }

        return $result;
    }

    #[\Override]
    public function getKeys(): Set
    {
        return new DSet($this->getKeysArray());
    }

    #[\Override]
    public function getValues(): Vec
    {
        return new DVec($this->getValuesArray());
    }

    #[\Override]
    public function getPairsArray(): array
    {
        $result = [];

        foreach ($this->items as $wrappedKey) {
            $result[] = new Pair($wrappedKey->key, $this->items[$wrappedKey]);
        }

        return $result;
    }

    #[\Override]
    public function set(mixed $key, mixed $value): static
    {
        $this->freezer->protect();

        $this->items[$this->mappedKeys->get($key)] = $value;

        return $this;
    }

    #[\Override]
    public function current(): mixed
    {
        return $this->items[$this->items->current()];
    }

    #[\Override]
    public function next(): void
    {
        $this->items->next();
    }

    #[\Override]
    public function key(): mixed
    {
        return $this->items->current()->key;
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->items->valid();
    }

    #[\Override]
    public function get(mixed $key): mixed
    {
        try {
            return $this->items[$this->mappedKeys->get($key)];
        } catch (\UnexpectedValueException $exception) {
            $message = 'Missing '.get_debug_type($key).' key';

            if (!\is_object($key)) {
                $message .= ': '.var_export($key, return: true);
            }

            throw new MissingKeyException($message, previous: $exception);
        }
    }

    #[\Override]
    public function isEmpty(): bool
    {
        return 0 === $this->items->count();
    }

    #[\Override]
    public function isNotEmpty(): bool
    {
        return 0 !== $this->items->count();
    }

    #[\Override]
    public function count(): int
    {
        return $this->items->count();
    }

    #[\Override]
    public function removeAllValues(iterable $values): static
    {
        $this->freezer->protect();

        foreach ($values as $removedValue) {
            foreach ($this->items as $wrappedKey) {
                if ($this->items[$wrappedKey] === $removedValue) {
                    $this->items->offsetUnset($wrappedKey);

                    break;
                }
            }
        }

        return $this;
    }

    #[\Override]
    public function removeAllKeys(iterable $keys): static
    {
        $this->freezer->protect();

        foreach ($keys as $key) {
            $this->items->offsetUnset($this->mappedKeys->get($key));
        }

        return $this;
    }

    #[\Override]
    public function contains(mixed $value): bool
    {
        foreach ($this->items as $key) {
            if ($this->items[$key] === $value) {
                return true;
            }
        }

        return false;
    }

    #[\Override]
    public function hasKey(mixed $key): bool
    {
        return $this->items->offsetExists($this->mappedKeys->get($key));
    }

    #[\Override]
    public function single(): Pair
    {
        $key = $this->singleKey();

        return new Pair($key, $this->get($key));
    }

    #[\Override]
    public function singleValue(): mixed
    {
        $key = $this->singleKey();

        return $this->get($key);
    }

    #[\Override]
    public function singleKey(): mixed
    {
        if (1 !== $this->count()) {
            throw new NoSingleElementException('The map has '.$this->count().' items instead of exactly one.');
        }

        $this->items->rewind();

        return $this->items->current()->key;
    }

    #[\Override]
    public function flip(): Map
    {
        $result = new static();

        foreach ($this as $key => $value) {
            $result->set($value, $key);
        }

        return $result;
    }

    /**
     * @param K $key
     *
     * @return K
     */
    protected static function enforceKeyType(mixed $key): mixed
    {
        throw new \LogicException('Not implemented. '.__METHOD__.' needs to be overridden.');
    }

    /**
     * @phpstan-assert-if-true K $value
     */
    protected static function isValidKey(mixed $value): bool
    {
        throw new \LogicException('Not implemented. '.__METHOD__.' needs to be overridden.');
    }
}
