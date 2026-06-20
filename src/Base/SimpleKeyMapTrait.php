<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\Freezer;
use Veelkoov\Debris\Exception\MissingKeyException;
use Veelkoov\Debris\Exception\NoSingleElementException;
use Veelkoov\Debris\Maps\Pair;

/**
 * @template K of int|string
 * @template V of object|scalar|null
 */
trait SimpleKeyMapTrait
{
    /**
     * @use EveryMapTrait<K, V>
     */
    use EveryMapTrait;

    protected readonly Freezer $freezer;

    /**
     * @var array<K, V>
     */
    protected array $items = [];

    //
    // ===== CONSTRUCTOR =======================================
    //

    /**
     * @param iterable<K, V> $items
     */
    final public function __construct(iterable $items = [], bool $frozen = false)
    {
        $this->freezer = new Freezer($this, false);

        $this->setAll($items);

        if ($frozen) {
            $this->freezer->freeze();
        }
    }

    //
    // ===== ADD ===============================================
    //

    public function set(mixed $key, mixed $value): static
    {
        $this->freezer->protect();

        $this->items[$key] = $value;

        return $this;
    }

    //
    // ===== ITERATION =========================================
    //

    #[\Override]
    public function current(): mixed
    {
        $result = current($this->items);

        if (false === $result) {
            throw new \Error('No current element.');
        }

        return $result;
    }

    #[\Override]
    public function next(): void
    {
        next($this->items);
    }

    #[\Override]
    public function key(): mixed
    {
        $result = key($this->items);

        if (null === $result) {
            throw new \Error('No current key.');
        }

        return $result;
    }

    #[\Override]
    public function valid(): bool
    {
        return false !== current($this->items);
    }

    #[\Override]
    public function rewind(): void
    {
        reset($this->items);
    }

    //
    // ===== GET ARRAY =========================================
    //

    public function getKeysArray(): array
    {
        return array_keys($this->items);
    }

    public function getValuesArray(): array
    {
        return array_values($this->items);
    }

    public function getValues(): Vec
    {
        return new DVec(array_values($this->items));
    }

    public function getPairsArray(): array
    {
        $result = [];

        foreach ($this->items as $key => $value) {
            $result[] = new Pair($key, $this->items[$key]);
        }

        return $result;
    }

    /**
     * @return array<K, V>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    //
    // ===== ACCESSORS =========================================
    //

    public function get(mixed $key): mixed
    {
        if (!$this->hasKey($key)) {
            throw new MissingKeyException('Missing '.get_debug_type($key).' key: '.var_export($key, return: true));
        }

        return $this->items[$key];
    }

    //
    // ===== EMPTY AND COUNT ===================================
    //

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

    //
    // ===== REMOVE ============================================
    //

    #[\Override]
    public function removeAllValues(iterable $values): static
    {
        $this->freezer->protect();

        foreach ($values as $removedValue) {
            foreach ($this->items as $key => $value) {
                if ($value === $removedValue) {
                    unset($this->items[$key]);

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
            unset($this->items[$key]);
        }

        return $this;
    }

    //
    // ===== CONTAINS ==========================================
    //

    #[\Override]
    public function contains(mixed $value): bool
    {
        return \in_array($value, $this->items, true);
    }

    #[\Override]
    public function hasKey(mixed $key): bool
    {
        return static::isValidKey($key) && \array_key_exists($key, $this->items);
    }

    //
    // ===== SINGLE ============================================
    //

    public function single(): Pair
    {
        $key = $this->singleKey();

        return new Pair($key, $this->items[$key]);
    }

    public function singleValue(): mixed
    {
        return $this->items[$this->singleKey()];
    }

    public function singleKey(): mixed
    {
        if (1 !== \count($this->items)) {
            throw new NoSingleElementException('The map has '.$this->count().' items instead of exactly one.');
        }

        return array_key_first($this->items);
    }

    //
    // ===== OTHER ============================================
    //

    public function flip(): Map
    {
        $result = new DMap();

        foreach ($this as $key => $value) {
            $result->set($value, $key);
        }

        return $result;
    }
}
