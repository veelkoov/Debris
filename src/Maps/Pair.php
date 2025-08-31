<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 */
final readonly class Pair implements \JsonSerializable
{
    /**
     * @param K $key
     * @param V $value
     */
    public function __construct(
        public mixed $key,
        public mixed $value,
    ) {}

    public function jsonSerialize(): mixed
    {
        return [$this->key, $this->value];
    }
}
