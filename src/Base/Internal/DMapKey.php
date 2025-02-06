<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

/**
 * @template T
 *
 * @internal
 */
final readonly class DMapKey
{
    /**
     * @param T $key
     */
    public function __construct(
        public mixed $key,
    ) {} // @codeCoverageIgnore
}
