<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

/**
 * @template K of object|scalar|null
 *
 * @internal
 */
final readonly class MapKey
{
    /**
     * @param K $key
     */
    public function __construct(
        public mixed $key,
    ) {} // @codeCoverageIgnore
}
