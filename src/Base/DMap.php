<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

use Veelkoov\Debris\Base\Internal\DMapKey;
use Veelkoov\Debris\Base\Internal\DMapKeyMapper;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 */
class DMap
{
    /**
     * @var \SplObjectStorage<object, V>
     */
    protected \SplObjectStorage $items;

    /**
     * @var DMapKeyMapper<K>
     */
    protected readonly DMapKeyMapper $mappedKeys;

    public function __construct()
    {
        $this->items = new \SplObjectStorage();
        $this->mappedKeys = new DMapKeyMapper();
    }

    /**
     * @param K $key
     * @param V $value
     *
     * @return $this
     */
    public function set(mixed $key, mixed $value): static
    {
        $this->items[$this->mappedKeys->get($key)] = $value;

        return $this;
    }

    /**
     * @param K $key
     *
     * @return V
     */
    public function get(mixed $key): mixed
    {
        return $this->items[$this->mappedKeys->get($key)];
    }

    /**
     * @return list<K>
     */
    public function getKeys(): array
    {
        $result = [];

        foreach ($this->items as $key) {
            $result[] = $key instanceof DMapKey ? $key->key : $key;
        }

        return $result; // @phpstan-ignore return.type (Set items key type properly)
    }
}
