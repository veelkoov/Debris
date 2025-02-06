<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base;

/**
 * @template T of object|scalar|null
 */
class DSet
{
    /**
     * @var DMap<T, null>
     */
    private DMap $items;

    /**
     * @param iterable<T> $items
     */
    public function __construct(iterable $items = [])
    {
        $this->items = new DMap();

        foreach ($items as $item) {
            $this->items->set($item, null);
        }
    }

    /**
     * @return list<T>
     */
    public function getValues(): array
    {
        return $this->items->getKeys();
    }
}
