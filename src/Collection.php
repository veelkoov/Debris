<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 *
 * @extends \Traversable<K, V>
 */
interface Collection extends \Traversable, \JsonSerializable, \Countable
{
    public function isEmpty(): bool;

    public function isNotEmpty(): bool;
}
