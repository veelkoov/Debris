<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

/**
 * @template K of object|scalar|null
 * @template V of object|scalar|null
 */
final readonly class DPair
{
    /**
     * @param K $key
     * @param V $value
     */
    public function __construct(
        public mixed $key,
        public mixed $value,
    ) {} // @codeCoverageIgnore
}
